<?php

namespace NSScottyPlugin\Settings\WordPress\General;

if (!defined('ABSPATH')) {
    exit;
}

class ExternalAccess
{
    public static function boot()
    {
        return new static();
    }

    public function __construct()
    {
        $options = NSScottyPlugin()->options->get('wordpress.general.external_access');

        // Disable REST API
        // https://www.wpbeginner.com/plugins/how-to-disable-json-rest-api-in-wordpress/
        if (!$options['wp_rest_api']['enabled']) {
            // Filters for WP-API version 1.x
            add_filter('json_enabled', '__return_false');
            add_filter('json_jsonp_enabled', '__return_false');

            add_filter('rest_enabled', '__return_false');
            add_filter('rest_jsonp_enabled', '__return_false');
            add_filter('rest_authentication_errors', function ($result) {
                if (!empty($result)) {
                    return $result;
                }
                if (!is_user_logged_in()) {
                    return new \WP_Error('rest_not_logged_in', __('You are not currently logged in.', 'scotty'), ['status' => 401]);
                }
                return $result;
            });
        }

        // Disable XML-RPC
        // https://www.wpbeginner.com/plugins/how-to-disable-xml-rpc-in-wordpress/
        // https://www.wpbeginner.com/plugins/how-to-disable-xml-rpc-pingback-in-wordpress/

        if (!$options['xmlrpc']['enabled']) {
            add_filter('xmlrpc_enabled', '__return_false');
            add_filter('xmlrpc_methods', function ($methods) {
                return [];
            });
            add_filter('xmlrpc_element_limit', function ($limit) {
                return 0;
            });
            add_filter('xmlrpc_login_error', function ($error) {
                return new \Error(403, __('XML-RPC services are disabled on this site.', 'scotty'));
            });
            add_filter('wp_headers', function ($headers) {
                unset($headers['X-Pingback']);
                return $headers;
            });
        }
    }
}
