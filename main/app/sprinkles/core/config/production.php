<?php

/**
 * Default production config file for UserFrosting.  You may override/extend this in your site's configuration file to customize deploy settings.
 *
 */

return [
    'assets' => [
        'use_raw' => FALSE
    ],
    'cache' => [
        'twig' => TRUE
    ],
    'debug' => [
        'twig' => FALSE,
        'auth' => FALSE,
        'smtp' => FALSE
    ],
    // Slim settings - see http://www.slimframework.com/docs/objects/application.html#slim-default-settings
    'settings' => [
        'routerCacheFile' => \UserFrosting\ROOT_DIR . '/' . \UserFrosting\APP_DIR_NAME . '/' . \UserFrosting\CACHE_DIR_NAME . '/' . 'routes.cache',
        'displayErrorDetails' => FALSE
    ],
    'site' => [
        'analytics' => [
            'google' => [
                'enabled' => TRUE
            ]
        ],
        'debug' => [
            'ajax' => FALSE,
            'info' => FALSE
        ]
    ],
    'php' => [
        'display_errors' => 'false',
        'log_errors' => 'true'
    ]
];
