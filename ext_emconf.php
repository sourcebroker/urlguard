<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'URL Guard',
    'description' => 'Adds support for typolink addQueryString to allow to include only specific url vars.',
    'category' => 'services',
    'version' => '0.1.2',
    'state' => 'beta',
    'uploadFolder' => false,
    'clearCacheOnLoad' => true,
    'author' => 'SourceBroker Team',
    'author_email' => 'office@sourcebroker.net',
    'author_company' => 'SourceBroker',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '6.2.0-9.0.99',
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
