<?php

namespace NSScottyPlugin\Traits;

trait CommentMetaTrait
{
  /**
   * Get the count of orphan commentmeta.

   * @return int
   */
  public function getOrphanCommentMetaCount()
  {
    global $wpdb;

    return $wpdb->get_var("SELECT COUNT(meta_id) FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_ID FROM $wpdb->comments)");
  }

  /**
   * Get the orphan commentmeta.
   *
   * @return array
   */
  public function getOrphanCommentMeta()
  {
    global $wpdb;

    return $wpdb->get_results("SELECT meta_id, comment_id, meta_key, meta_value FROM $wpdb->commentmeta WHERE comment_id NOT IN (SELECT comment_ID FROM $wpdb->comments)");
  }

  /**
   * Delete all orphan commentmeta.
   *
   * @return void
   */
  public function deleteOrphanCommentMeta()
  {
    global $wpdb;

    $query_results = $wpdb->get_results("SELECT comment_id, meta_key FROM {$wpdb->prefix}commentmeta WHERE comment_id NOT IN (SELECT comment_ID FROM {$wpdb->prefix}comments)");
    if ($query_results) {
      foreach ($query_results as $result) {
        $comment_id = (int) $result->comment_id;
        if (0 === $comment_id) {
          $wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}commentmeta WHERE comment_id = %d AND meta_key = %s", $result->comment_id, $result->meta_key));
        } else {
          delete_comment_meta($comment_id, $result->meta_key);
        }
      }
    }
  }


  /**
   * Get the count of duplicate commentmeta.
   *
   * @return int
   */
  public function getDuplicateCommentMetaCount()
  {
    global $wpdb;

    $count = 0;

    $query = $wpdb->get_col($wpdb->prepare("SELECT COUNT(meta_id) AS count FROM $wpdb->commentmeta GROUP BY comment_id, meta_key, meta_value HAVING count > %d", 1));
    if (is_array($query)) {
      $count = array_sum(array_map('intval', $query));
    }
    return $count;
  }

  /**
   * Get the duplicate commentmeta.
   *
   * @return array
   */
  public function getDuplicateCommentMeta()
  {
    global $wpdb;

    $query = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $wpdb->commentmeta pm WHERE (meta_key, meta_value) IN (SELECT meta_key, meta_value FROM $wpdb->commentmeta WHERE comment_id = pm.comment_id GROUP BY meta_key, meta_value HAVING COUNT(*) > %d)",
        1
      )
    );

    return $query;
  }
}
