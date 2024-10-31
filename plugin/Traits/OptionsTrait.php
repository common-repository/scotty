<?php

namespace NSScottyPlugin\Traits;

trait OptionsTrait
{
  /**
   * Get the count of options.
   *
   * @return int
   */
  public function getOptionsCount()
  {
    global $wpdb;

    return $wpdb->get_var("SELECT COUNT(option_id) FROM $wpdb->options");
  }

  /**
   * Get the count of transient options.
   *
   * @return int
   */
  public function getTransientOptionsCount()
  {
    global $wpdb;

    return $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(option_id) FROM $wpdb->options WHERE option_name LIKE(%s)",
        '%\_transient\_%'
      )
    );
  }

  /**
   * Get the list of transient options.
   *
   * @return array
   */
  public function getTransientOptions()
  {
    global $wpdb;

    return $wpdb->get_results(
      "SELECT option_id, option_name, option_value FROM $wpdb->options WHERE option_name LIKE '%\_transient\_%'"
    );
  }

  /**
   * Delete all transient options.
   *
   * @return void
   */
  public function deleteTransientOptions()
  {
    global $wpdb;

    $query = $wpdb->get_col(
      $wpdb->prepare(
        "SELECT option_name FROM $wpdb->options WHERE option_name LIKE(%s)",
        '%\_transient\_%'
      )
    );
    if ($query) {
      foreach ($query as $option_name) {
        if (strpos($option_name, '_site_transient_') !== false) {
          delete_site_transient(
            str_replace('_site_transient_', '', $option_name)
          );
        } else {
          delete_transient(str_replace('_transient_', '', $option_name));
        }
      }
    }
  }

  /**
   * Delete a transient option.
   *
   * @param int $option_id
   * @return void
   */
  public function deleteTransientOption($option_id)
  {
    global $wpdb;

    $query = $wpdb->get_col(
      $wpdb->prepare(
        "SELECT option_name FROM $wpdb->options WHERE option_id = %d",
        $option_id
      )
    );

    if ($query) {
      foreach ($query as $option_name) {
        if (strpos($option_name, '_site_transient_') !== false) {
          delete_site_transient(
            str_replace('_site_transient_', '', $option_name)
          );
        } else {
          delete_transient(str_replace('_transient_', '', $option_name));
        }
      }
    }
  }

  /**
   * Reset all Scotty options.
   *
   * @return void
   */
  public function resetOptions()
  {
    NSScottyPlugin()->options->reset();
  }
}
