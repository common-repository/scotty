<?php

namespace NSScottyPlugin\Settings\WordPress\Admin;

if (!defined('ABSPATH')) {
  exit;
}

class Menu
{
  public static function boot()
  {
    return new static();
  }

  public function __construct()
  {
    $options = NSScottyPlugin()->options->get('wordpress.admin.menu');

    // Disabled the dashboard menu
    if (!$options['display_dashboard']['enabled']) {
      add_action('admin_menu', function () {
        remove_menu_page('index.php');
      });
    }

    // Disabled the posts menu
    if (!$options['display_posts']['enabled']) {
      add_action('admin_menu', function () {
        remove_menu_page('edit.php');
      });
    }
  }

  protected function removeMenu($label)
  {
    global $menu;

    end($menu);
    while (prev($menu)) {
      $value = explode(' ', $menu[key($menu)][0]);
      if ($value[0] != null && $value[0] == $label) {
        unset($menu[key($menu)]);
      }
    }
  }
}
