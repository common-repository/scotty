<?php

namespace NSScottyPlugin\Settings\WordPress\Admin;

if (!defined('ABSPATH')) {
  exit;
}

class Appearance
{
  public static function boot()
  {
    return new static();
  }

  public function __construct()
  {
    $options = NSScottyPlugin()->options->get('wordpress.admin.appearance');

    // Disabled the admin footer credit text
    if (!$options['display_footer_credit']['enabled']) {
      add_filter('admin_footer_text', '__return_false');
    }

    // Disabled the admin footer version text
    if (!$options['display_footer_version']['enabled']) {
      add_filter('update_footer', '__return_false', 99);
    }

    // Disabled the Welcome Panel
    if (!$options['display_welcome_panel']['enabled']) {
      add_action('admin_init', function () {
        remove_action('welcome_panel', 'wp_welcome_panel');
      });
    }
  }
}
