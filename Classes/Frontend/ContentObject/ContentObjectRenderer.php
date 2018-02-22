<?php

namespace SourceBroker\Urlguard\Frontend\ContentObject;

/*
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class ContentObjectRenderer extends \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
{
    /**
     * Gets the query arguments and assembles them for URLs.
     * Xclassed to add support for 'include' and 'includePluginsNamespaces' options.
     *
     * @param array $conf Configuration
     * @param array $overruleQueryArguments Multidimensional key/value pairs that overrule incoming query arguments
     * @param bool $forceOverruleArguments If set, key/value pairs not in the query but the overrule array will be set
     * @return string The URL query part (starting with a &)
     */
    public function getQueryArguments($conf, $overruleQueryArguments = [], $forceOverruleArguments = false)
    {
        switch ((string)$conf['method']) {
            case 'GET':
                $currentQueryArray = GeneralUtility::_GET();
                break;
            case 'POST':
                $currentQueryArray = GeneralUtility::_POST();
                break;
            case 'GET,POST':
                $currentQueryArray = GeneralUtility::_GET();
                ArrayUtility::mergeRecursiveWithOverrule($currentQueryArray, GeneralUtility::_POST());
                break;
            case 'POST,GET':
                $currentQueryArray = GeneralUtility::_POST();
                ArrayUtility::mergeRecursiveWithOverrule($currentQueryArray, GeneralUtility::_GET());
                break;
            default:
                $currentQueryArray = GeneralUtility::explodeUrl2Array($this->getEnvironmentVariable('QUERY_STRING'), true);
        }
        $allowedUrlNamespaces = [];
        // By default option includePluginsNamespaces is active if not set.
        if (!isset($conf['includePluginsNamespaces'])
            || !empty($conf['includePluginsNamespaces'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'] as $extensionName => $settings) {
                if (!empty($settings['plugins'])) {
                    $extensionName = str_replace(' ', '', ucwords(str_replace('_', ' ', $extensionName)));
                    foreach ($settings['plugins'] as $pluginName => $pluginSettings) {
                        $allowedUrlNamespaces[] = 'tx_' . strtolower($extensionName . '_' . $pluginName);
                    }
                }
            }
        }
        if (!empty(trim($conf['include']))) {
            $allowedUrlNamespaces = array_merge(
                $allowedUrlNamespaces,
                GeneralUtility::trimExplode(',', $conf['include'])
            );
        }
        if (!empty($allowedUrlNamespaces)) {
            $conf['exclude'] = implode(',',
                array_unique(array_merge(
                    GeneralUtility::trimExplode(',', $conf['exclude']),
                    array_filter(
                        array_keys($currentQueryArray),
                        function ($getVarNamespace) use ($allowedUrlNamespaces) {
                            return !in_array($getVarNamespace, $allowedUrlNamespaces);
                        }
                    ))));
        }
        if ($conf['exclude']) {
            $exclude = str_replace(',', '&', $conf['exclude']);
            $exclude = GeneralUtility::explodeUrl2Array($exclude, true);
            // never repeat id
            $exclude['id'] = 0;
            $newQueryArray = ArrayUtility::arrayDiffAssocRecursive($currentQueryArray, $exclude);
        } else {
            $newQueryArray = $currentQueryArray;
        }
        if ($forceOverruleArguments) {
            ArrayUtility::mergeRecursiveWithOverrule($newQueryArray, $overruleQueryArguments);
        } else {
            ArrayUtility::mergeRecursiveWithOverrule($newQueryArray, $overruleQueryArguments, false);
        }
        return GeneralUtility::implodeArrayForUrl('', $newQueryArray, '', false, true);
    }
}
