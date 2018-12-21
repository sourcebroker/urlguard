<?php

call_user_func(function () {
    $configuration = is_string($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['urlguard']) ?
        @unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['urlguard']) : [];
    if (!empty($configuration['enableXclassForContentObjectRenderer'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class] = [
            'className' => \SourceBroker\Urlguard\Frontend\ContentObject\ContentObjectRenderer::class
        ];
    }
});
