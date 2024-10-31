<?php

namespace NSScottyPlugin\Traits;

trait UserMetaTrait
{
  /**
   * Get the count of orphan usermeta.
   *
   * @return int
   */
  public function getOrphanUserMetaCount()
  {
    global $wpdb;

    return $wpdb->get_var("SELECT COUNT(umeta_id) FROM $wpdb->usermeta WHERE user_id NOT IN (SELECT ID FROM $wpdb->users)");
  }

  /**
   * Delete orphan usermeta.
   *
   * @return void
   */
  public function deleteOrphanUserMeta()
  {
    global $wpdb;

    $query = $wpdb->get_results("SELECT user_id, meta_key FROM $wpdb->usermeta WHERE user_id NOT IN (SELECT ID FROM $wpdb->users)");
    if ($query) {
      foreach ($query as $meta) {
        $user_id = (int) $meta->user_id;
        if (0 === $user_id) {
          $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->usermeta WHERE user_id = %d AND meta_key = %s", $user_id, $meta->meta_key));
        } else {
          delete_user_meta($user_id, $meta->meta_key);
        }
      }
    }
  }

  /**
   * Delete a usermeta.
   *
   * @return void
   */
  public function deleteUserMetaWithId($id)
  {
    global $wpdb;
    $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->usermeta WHERE umeta_id = %d", $id));
  }

  /**
   * Get the orphan usermeta.
   *
   * @return array
   */
  public function getOrphanUserMeta()
  {
    global $wpdb;

    return $wpdb->get_results("SELECT umeta_id, meta_key, meta_value FROM $wpdb->usermeta WHERE user_id NOT IN (SELECT ID FROM $wpdb->users)");
  }

  /**
   * Get the count of duplicate usermeta.
   *
   * @return int
   */
  public function getDuplicateUserMetaCount()
  {
    global $wpdb;

    $count = 0;

    $query = $wpdb->get_col($wpdb->prepare("SELECT COUNT(umeta_id) AS count FROM $wpdb->usermeta GROUP BY user_id, meta_key, meta_value HAVING count > %d", 1));
    if (is_array($query)) {
      $count = array_sum(array_map('intval', $query));
    }
    return $count;
  }

  /**
   * Get the duplicate usermeta.
   *
   * @return array
   */
  public function getDuplicateUserMeta()
  {
    global $wpdb;

    $query = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $wpdb->usermeta um WHERE (meta_key, meta_value) IN (SELECT meta_key, meta_value FROM $wpdb->usermeta WHERE user_id = um.user_id GROUP BY meta_key, meta_value HAVING COUNT(*) > %d)",
        1
      )
    );

    return $query;
  }
}
