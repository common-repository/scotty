<?php

namespace NSScottyPlugin\Traits;

trait PostMetaTrait
{
  /**
   * Delete all orphan post meta.
   *
   * @return void
   */
  public function deleteOrphanPostMeta()
  {
    global $wpdb;

    $query = $wpdb->get_results(
      "SELECT post_id, meta_key FROM $wpdb->postmeta WHERE post_id NOT IN (SELECT ID FROM $wpdb->posts)"
    );
    if ($query) {
      foreach ($query as $meta) {
        $post_id = (int)$meta->post_id;
        if (0 === $post_id) {
          $wpdb->query(
            $wpdb->prepare(
              "DELETE FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s",
              $post_id,
              $meta->meta_key
            )
          );
        } else {
          delete_post_meta($post_id, $meta->meta_key);
        }
      }
    }

    return $query;
  }

  /**
   * Delete orphan postmeta with a specific ID.
   *
   * @param int $id
   */
  public function deletePostMetaWithId($id)
  {
    global $wpdb;

    return $wpdb->query(
      $wpdb->prepare("DELETE FROM $wpdb->postmeta WHERE meta_id = %d", $id)
    );
  }

  /**
   * Get the count of duplicate postmeta.
   *
   * @return int
   */
  public function getDuplicatePostMetaCount()
  {
    global $wpdb;

    $count = 0;

    $query = $wpdb->get_col(
      $wpdb->prepare(
        "SELECT COUNT(meta_id) AS count FROM $wpdb->postmeta GROUP BY post_id, meta_key, meta_value HAVING count > %d",
        1
      )
    );
    if (is_array($query)) {
      $count = array_sum(array_map('intval', $query));
    }

    return $count;
  }

  /**
   * Get the duplicate postmeta.
   *
   * @return array
   */
  public function getDuplicatePostMeta()
  {
    global $wpdb;

    $query = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT meta_id AS id, post_id, meta_key, meta_value FROM $wpdb->postmeta pm WHERE (meta_key, meta_value) IN (SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = pm.post_id GROUP BY meta_key, meta_value HAVING COUNT(*) > %d);",
        1
      )
    );

    return $query;
  }

  /**
   * Delete all duplicate postmeta.
   *
   * @return void
   */
  public function deleteDuplicatePostMeta()
  {
    global $wpdb;

    // Query to find duplicate post meta
    $query = $wpdb->get_results(
      "SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) AS ids, post_id, COUNT(*) AS count
             FROM $wpdb->postmeta
             GROUP BY post_id, meta_key, meta_value
             HAVING count > 1"
    );

    if ($query) {
      foreach ($query as $meta) {
        // Convert the comma-separated IDs into an array
        $ids = array_map('intval', explode(',', $meta->ids));

        // Remove the first ID from the array (keeping the latest entry)
        array_shift($ids);

        // placeholders
        $ids_placeholders = implode(',', array_fill(0, count($ids), '%d'));

        // Delete the duplicate meta entries
        $wpdb->query(
          $wpdb->prepare(
            "DELETE FROM $wpdb->postmeta WHERE post_id = %d AND meta_id IN ($ids_placeholders)",
            $meta->post_id,
            ...$ids
          )
        );
      }
    }
  }

  /**
   * Get the count of oembed postmeta.
   *
   * @return int
   */
  public function getOEmbedPostMetaCount()
  {
    global $wpdb;

    $count = $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(meta_id) FROM $wpdb->postmeta WHERE meta_key LIKE(%s)",
        '%_oembed_%'
      )
    );

    return $count;
  }

  /**
   * Get the oembed postmeta.
   *
   * @return array
   */
  public function getOEmbedPostMeta()
  {
    global $wpdb;

    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $wpdb->postmeta WHERE meta_key LIKE(%s)",
        '%_oembed_%'
      )
    );
  }

  /**
   * Get the list of orphan postmeta.
   *
   * @param string $key
   * @return int
   */
  protected function getOrphanPostMeta()
  {
    global $wpdb;

    return $wpdb->get_results(
      "SELECT meta_id AS id, post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE post_id NOT IN (SELECT ID FROM $wpdb->posts)"
    );
  }

  /**
   * Get the count of orphan postmeta.
   *
   * @return int
   */
  protected function getOrphanPostMetaCount()
  {
    global $wpdb;

    return $wpdb->get_var(
      "SELECT COUNT(meta_id) FROM $wpdb->postmeta WHERE post_id NOT IN (SELECT ID FROM $wpdb->posts)"
    );
  }
}
