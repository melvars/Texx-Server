<?php

/**
 * Default development config file for UserFrosting. Sets up UserFrosting for easier development.
 *
 */

return [
    'assets' => [
        'use_raw' => TRUE
    ],
    'cache' => [
        'twig' => FALSE
    ],
    'debug' => [
        'twig' => TRUE,
        'auth' => TRUE,
        'smtp' => TRUE
    ],
    // Slim settings - see http://www.slimframework.com/docs/objects/application.html#slim-default-settings
    'settings' => [
        'displayErrorDetails' => TRUE
    ],
    'site' => [
        'debug' => [
            'ajax' => TRUE,
            'info' => TRUE
        ]
    ]
];