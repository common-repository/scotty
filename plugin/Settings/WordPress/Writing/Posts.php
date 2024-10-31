<?php

namespace NSScottyPlugin\Settings\WordPress\Writing;

if (!defined('ABSPATH')) {
  exit;
}

class Posts
{
  public static function boot()
  {
    return new static();
  }

  public function __construct()
  {
    $options = NSScottyPlugin()->options->get('wordpress.writing.posts');

    // Revisions settings
    if ($options['number_of_revisions']['enabled']) {
      $value = $options['number_of_revisions']['value'];
      add_filter('wp_revisions_to_keep', function ($num, $post) use ($value) {
        return $value;
      }, 10, 2);
    }
  }
}
