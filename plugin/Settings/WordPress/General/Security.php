<?php

namespace NSScottyPlugin\Settings\WordPress\General;

if (!defined('ABSPATH')) {
    exit;
}

class Security
{
    public static function boot()
    {
        return new static();
    }

    public function __construct()
    {
        $options = NSScottyPlugin()->options->get('wordpress.general.security');

        // Remove WordPress version from the header
        if (!$options['display_wordpress_version']['enabled']) {
            remove_action(
                'wp_head',
                'wp_generator'
            );

            add_filter('the_generator', function () {
                return '';
            });
        }

        // Remove login errors
        if (!$options['display_login_errors']['enabled']) {
            add_filter('login_errors', function () {
                return __('Login error', 'scotty');
            });
        }

        // Remove authentication by email
        if (!$options['display_authenticate_by_email']['enabled']) {
            remove_filter('authenticate', 'wp_authenticate_email_password', 20);
        }
    }
}
