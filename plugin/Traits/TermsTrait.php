<?php

namespace NSScottyPlugin\Traits;

trait TermsTrait
{
  /**
   * Get the count of unused terms
   *
   * @return string|null
   */
  public function getUnusedTermsCount()
  {
    global $wpdb;

    $excluded_termids = $this->getExcludedTermIds();
    $excluded_termids_placeholder = implode(', ', array_fill(0, count($excluded_termids), '%d'));

    return $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(t.term_id) FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.count = %d AND t.term_id NOT IN ($excluded_termids_placeholder)",
        0,
        ...$excluded_termids
      )
    );
  }

  public function getUnusedTerms()
  {
    global $wpdb;

    $excluded_termids = $this->getExcludedTermIds();
    $excluded_termids_placeholder = implode(', ', array_fill(0, count($excluded_termids), '%d'));

    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT t.term_id, t.name, t.slug FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.count = %d AND t.term_id NOT IN ($excluded_termids_placeholder)",
        0,
        ...$excluded_termids
      )
    );
  }

  public function deleteUnusedTerms()
  {
    global $wpdb;

    $excluded_termids = $this->getExcludedTermIds();
    $excluded_termids_placeholder = implode(', ', array_fill(0, count($excluded_termids), '%d'));

    $wpdb->query(
      $wpdb->prepare(
        "DELETE t, tt FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.count = %d AND t.term_id NOT IN ($excluded_termids_placeholder)",
        0,
        ...$excluded_termids
      )
    );
  }

  public function deleteUnusedTerm($term_id)
  {
    global $wpdb;

    $wpdb->query(
      $wpdb->prepare(
        "DELETE t, tt FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE t.term_id = %d",
        $term_id
      )
    );
  }

  /**
   * Get excluded term IDs
   *
   * @return array Excluded term IDs
   */
  private function getExcludedTermIds()
  {
    $default_term_ids = $this->getDefaultTaxonomyTermIds();
    if (!is_array($default_term_ids)) {
      $default_term_ids = [];
    }
    $parent_term_ids = $this->getParentTermIds();
    if (!is_array($parent_term_ids)) {
      $parent_term_ids = [];
    }
    $excluded_termids = array_merge($default_term_ids, $parent_term_ids);

    return apply_filters('scotty_excluded_termids', $excluded_termids);
  }

  /**
   * Get all default taxonomy term IDs
   *
   * @return array Default taxonomy term IDs
   */
  private function getDefaultTaxonomyTermIds()
  {
    $taxonomies = get_taxonomies();
    $default_term_ids = [];
    if ($taxonomies) {
      $tax = array_keys($taxonomies);
      if ($tax) {
        foreach ($tax as $t) {
          $term_id = (int)get_option('default_' . $t);
          if ($term_id > 0) {
            $default_term_ids[] = $term_id;
          }
        }
      }
    }
    return $default_term_ids;
  }

  /**
   * Get terms that has a parent term
   *
   * @return array Parent term IDs
   */
  private function getParentTermIds()
  {
    global $wpdb;

    return $wpdb->get_col($wpdb->prepare("SELECT tt.parent FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.parent > %d", 0));
  }
}
