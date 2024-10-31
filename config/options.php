<?php

if (!defined('ABSPATH')) {
    exit();
}

/*
|--------------------------------------------------------------------------
| Plugin options
|--------------------------------------------------------------------------
|
| Here is where you can insert the options model of your plugin.
| These options model will store in WordPress options table
| (usually wp_options).
| You'll get these options by using `$plugin->options` property
|
*/

return [
    'version' => '1.0.0',
    'settings' => [
        'notifications' => [
            'posts' => [
                'revisions' => [
                    'enabled' => true,
                    'threshold' => 100
                ],
                'auto_draft' => [
                    'enabled' => true,
                    'threshold' => 100
                ],
                'deleted_posts' => [
                    'enabled' => false,
                    'threshold' => 50
                ]
            ]
        ],
    ],
    'wordpress' => [
        'general' => [
            'external_access' => [
                'wp_rest_api' => [
                    'enabled' => true
                ],
                'xmlrpc' => [
                    'enabled' => true
                ],
            ],
            'security' => [
                'display_wordpress_version' => [
                    'enabled' => true
                ],
                'display_login_errors' => [
                    'enabled' => true
                ],
                'display_authenticate_by_email' => [
                    'enabled' => true
                ],
            ]
        ],
        'admin' => [
            'appearance' => [
                'display_footer_credit' => [
                    'enabled' => true
                ],
                'display_footer_version' => [
                    'enabled' => true
                ],
                'display_welcome_panel' => [
                    'enabled' => true
                ],
            ],
            'menu' => [
                'display_dashboard' => [
                    'enabled' => true
                ],
                'display_posts' => [
                    'enabled' => true
                ],
            ]
        ],
        'writing' => [
            'posts' => [
                'number_of_revisions' => [
                    'enabled' => false,
                    'value' => 5
                ],
            ]
        ],
        'reading' => [
            'theme' => [
                'excerpt_length' => [
                    'enabled' => false,
                    'value' => 25
                ],
                'admin_bar' => [
                    'enabled' => false,
                ],
            ]
        ],
    ]
];
