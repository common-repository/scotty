<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\PreferencesTrait;

class PreferencesAjaxServiceProvider extends AjaxServiceProvider
{
  use PreferencesTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = ['get_preferences', 'update_preferences'];

  /**
   * Returns the preferences of the plugin
   *
   * @return void
   */
  public function get_preferences()
  {
    $preferences = NSScottyPlugin()->options->toArray();
    wp_send_json($preferences);
  }

  /**
   * Update the preferences of the plugin
   *
   * @return void
   */
  public function update_preferences()
  {

    [$preferences] = $this->useHTTPPost('preferences');

    $object = json_decode(stripslashes($preferences), true);

    NSScottyPlugin()->options->update($object);
    wp_send_json_success();
  }
}
