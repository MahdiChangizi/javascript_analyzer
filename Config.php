<?php 
/*
    * Configuration file for JavaScript Analyzer
    * This file contains environment variables used in the application.
*/
return [
    'WEBHOOK_URL' => 'https://discord.com/api/webhooks/1398620689485004882/rFfwCWqwTMgvStZssBDEWGNmUhSpl6R3FtnhKUQ2hmj0TBcFrjHnL1-paMGlolFKwfQk ',
    'PROJECT' => [
        [
            'NAME' => 'toplearn.com',
            'SAVE_PATH' => dirname(__DIR__) . '/storage/toplearn/',
            'URL' => 'https://toplearn.com',
            'FILE_NAMES' => [
                'modernizr.js' => '/Site/js/modernizr.js',
                'site.validate.min.js' => '/Site/js/site.validate.min.js'
            ],
        ]
    ]
];
