<?php

if (!defined('ABSPATH')) {
    exit();
}

return [
    /*
      |--------------------------------------------------------------------------
      | Logging Configuration
      |--------------------------------------------------------------------------
      |
      | Here you may configure the log settings for your plugin.
      |
      | Available Settings: "single", "daily", "errorlog".
      |
      | Set to false or 'none' to stop logging.
      |
      */

    'log' => 'errorlog',

    'log_level' => 'debug',

    /*
    |--------------------------------------------------------------------------
    | Screen options
    |--------------------------------------------------------------------------
    |
    | Here is where you can register the screen options for List Table.
    |
    */

    'screen_options' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom Post Types
    |--------------------------------------------------------------------------
    |
    | Here is where you can register the Custom Post Types.
    |
    */

    'custom_post_types' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom Taxonomies
    |--------------------------------------------------------------------------
    |
    | Here is where you can register the Custom Taxonomy Types.
    |
    */

    'custom_taxonomy_types' => [],

    /*
    |--------------------------------------------------------------------------
    | Shortcode
    |--------------------------------------------------------------------------
    |
    | Here is where you can register the Shortcode.
    |
    */

    'shortcodes' => [],

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | Here is where you can register all of the Widget for a plugin.
    |
    */

    'widgets' => [],

    /*
    |--------------------------------------------------------------------------
    | Ajax
    |--------------------------------------------------------------------------
    |
    | Here is where you can register your own Ajax actions.
    |
    */

    'ajax' => [
        '\NSScottyPlugin\Ajax\CommentMetaAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\CommentsAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\DatabaseAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\DiskAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\OptionsAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\PostMetaAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\PostsAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\TermMetaAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\TermRelationshipsAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\TermsAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\TrashAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\DuplicatesAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\OverviewAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\UserMetaAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\CronAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\ShortcodeAjaxServiceProvider',
        '\NSScottyPlugin\Ajax\PreferencesAjaxServiceProvider',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoload Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | init to your plugin. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [
        '\NSScottyPlugin\Providers\DashboardWidget',
        '\NSScottyPlugin\Providers\NSScottyPluginProvider'
    ],
];
