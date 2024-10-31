<?php

namespace NSScottyPlugin\Providers;

if (!defined('ABSPATH')) {
    exit;
}

use NSScottyPlugin\WPBones\Support\ServiceProvider;

class DashboardWidget extends ServiceProvider
{

    public function register()
    {
        add_action('wp_dashboard_setup', [$this, 'dashboard_widget']);
    }

    public function dashboard_widget()
    {
        wp_add_dashboard_widget(
            'wp_scotty_dashboard_widget_2',
            __('Scotty', 'scotty'),
            [$this, 'dashboard_widget_content'],
            null,
            null,
            'normal',
            'high'
        );
    }

    public function dashboard_widget_content()
    {
        NSScottyPlugin()
          ->view('dashboard.widget')
          ->withLocalizeScript('dashboard-widget/dashboard-widget', 'NSScottyPluginMantine', [
            'nonce' => wp_create_nonce('scotty-mantine'),
            'version' => NSScottyPlugin()->Version,
            'preferences' => NSScottyPlugin()->options->toArray(),
            'health' => wp_create_nonce('wp_rest'),
          ])
          ->withAdminAppsScript('dashboard-widget/dashboard-widget', true)
          ->render();
    }
}
