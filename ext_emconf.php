<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'URL Guard',
    'description' => 'Adds support for typolink addQueryString to allow to include only specific url vars.',
    'category' => 'services',
    'version' => '1.0.2',
    'state' => 'beta',
    'uploadFolder' => false,
    'clearCacheOnLoad' => true,
    'author' => 'SourceBroker Team',
    'author_email' => 'office@sourcebroker.dev',
    'author_company' => 'SourceBroker',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '7.6.0-9.5.999',
                ],
            'conflicts' =>
                [
                ],
            'suggests' =>
                [
                    'realurl' => ''
                ],
        ]
];
