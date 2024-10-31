<?php

namespace NSScottyPlugin\Traits;

trait TermMetaTrait
{
  /**
   * Get the count of orphan term meta.
   *
   * @return int
   */
  public function getOrphanTermMetaCount()
  {
    global $wpdb;

    return $wpdb->get_var(
      "SELECT COUNT(meta_id) FROM $wpdb->termmeta WHERE term_id NOT IN (SELECT term_id FROM $wpdb->terms)"
    );
  }

  /**
   * Get the orphan term meta.
   *
   * @return array
   */
  public function getOrphanTermMeta()
  {
    global $wpdb;

    $results = $wpdb->get_results(
      "SELECT * FROM $wpdb->termmeta WHERE term_id NOT IN (SELECT term_id FROM $wpdb->terms)"
    );

    return $results;
  }

  /**
   * Delete a term meta.
   *
   * @param int $id
   * @return void
   */
  public function deleteTermMetaWithId($id)
  {
    global $wpdb;

    $wpdb->delete($wpdb->termmeta, ['meta_id' => $id]);
  }

  /**
   * Delete all orphan term meta.
   *
   * @return void
   */
  public function deleteOrphanTermMeta()
  {
    global $wpdb;

    $query = $wpdb->get_results("SELECT term_id, meta_key FROM $wpdb->termmeta WHERE term_id NOT IN (SELECT term_id FROM $wpdb->terms)");
    if ($query) {
      foreach ($query as $meta) {
        $term_id = (int)$meta->term_id;
        if (0 === $term_id) {
          $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->termmeta WHERE term_id = %d AND meta_key = %s", $term_id, $meta->meta_key));
        } else {
          delete_term_meta($term_id, $meta->meta_key);
        }
      }
    }
  }

  /**
   * Get the count of duplicate term meta.
   *
   * @return int
   */
  public function getDuplicateTermMetaCount()
  {
    global $wpdb;

    $count = 0;

    $query = $wpdb->get_col($wpdb->prepare("SELECT COUNT(meta_id) AS count FROM $wpdb->termmeta GROUP BY term_id, meta_key, meta_value HAVING count > %d", 1));

    if (is_array($query)) {
      $count = array_sum(array_map('intval', $query));
    }

    return $count;
  }

  /**
   * Get the duplicate term meta.
   *
   * @return array
   */
  public function getDuplicateTermMeta()
  {
    global $wpdb;

    $query = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $wpdb->termmeta tm WHERE (meta_key, meta_value) IN (SELECT meta_key, meta_value FROM $wpdb->termmeta WHERE term_id = tm.term_id GROUP BY meta_key, meta_value HAVING COUNT(meta_id) > %d)",
        1
      )
    );

    return $query;
  }

  public function deleteDuplicateTermMeta()
  {
    global $wpdb;

    $query = $wpdb->get_results($wpdb->prepare("SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) AS ids, term_id, COUNT(*) AS count FROM $wpdb->termmeta GROUP BY term_id, meta_key, meta_value HAVING count > %d", 1));

    if ($query) {
      foreach ($query as $meta) {
        $ids = array_map('intval', explode(',', $meta->ids));
        $ids_placeholders = implode(',', array_fill(0, count($ids), '%d'));
        $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->termmeta WHERE term_id = %d AND meta_id IN ($ids_placeholders)", (int)$meta->term_id, ...$ids));
        array_pop($ids);
      }
    }
  }
}
