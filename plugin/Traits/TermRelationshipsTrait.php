<?php

namespace NSScottyPlugin\Traits;

trait TermRelationshipsTrait
{
    /**
     * Get the count of orphan term relationships
     *
     * @return string|null
     */
    public function getOrphanTermRelationshipsCount()
    {
        global $wpdb;

        $excluded_taxonomies_values = array_map('esc_sql', $this->getExcludedTaxonomies());
        $excluded_taxonomies_placeholders = implode(',', array_fill(0, count($excluded_taxonomies_values), '%s'));

        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(object_id) FROM $wpdb->term_relationships AS tr INNER JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy NOT IN ($excluded_taxonomies_placeholders) AND tr.object_id NOT IN (SELECT ID FROM $wpdb->posts)",
                ...$excluded_taxonomies_values
            )
        );
    }

    /**
     * Get excluded taxonomies
     *
     * @return array Excluded taxonomies
     */
    private function getExcludedTaxonomies()
    {
        $excluded_taxonomies = [];
        $excluded_taxonomies[] = 'link_category';

        return apply_filters('scotty_excluded_taxonomies', $excluded_taxonomies);
    }
}
