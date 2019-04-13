<?php

call_user_func(function () {
    $configuration = is_string($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['urlguard']) ?
        @unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['urlguard']) : [];
    if (!empty($configuration['enableXclassForContentObjectRenderer'])) {
        if (TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) <= 8007999) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class] = [
                'className' => \SourceBroker\Urlguard\Frontend\ContentObject\ContentObjectRenderer87::class
            ];
        } else {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class] = [
                'className' => \SourceBroker\Urlguard\Frontend\ContentObject\ContentObjectRenderer95::class
            ];
        }
    }
});
