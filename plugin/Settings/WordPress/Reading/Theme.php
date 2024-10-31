<?php

namespace NSScottyPlugin\Settings\WordPress\Reading;

if (!defined('ABSPATH')) {
  exit;
}

class Theme
{
  public static function boot()
  {
    return new static();
  }

  public function __construct()
  {
    $options = NSScottyPlugin()->options->get('wordpress.reading.theme');

    // Revisions settings
    if ($options['excerpt_length']['enabled']) {
      $value = $options['excerpt_length']['value'];
      add_filter('excerpt_length', function () use ($value) {
        return $value;
      });
    }

    // Theme admin bar
    if ($options['admin_bar']['enabled']) {
      if (!is_admin()) {
        add_filter('show_admin_bar', '__return_false');
        wp_deregister_script('admin-bar');
        wp_deregister_style('admin-bar');
        remove_action('wp_footer', 'wp_admin_bar_render', 1000);
        show_admin_bar(false);
      }
    }
  }
}
