<?php

namespace NSScottyPlugin\Traits;
trait PostsTrait
{
    use MantineUITrait;

    /**
     * Get the count of posts per status.
     *
     * @return array
     */
    public function getPostsCountPerStatus()
    {
        global $wpdb;

        $results = $wpdb->get_results(
            "SELECT
          CONCAT(UCASE(LEFT(post_status, 1)), SUBSTRING(post_status, 2)) AS name,
          CAST(COUNT(*) AS UNSIGNED) AS count,
          ROUND((COUNT(*) / (SELECT COUNT(*) FROM wp_posts) * 100), 2) AS value
       FROM $wpdb->posts
       GROUP BY post_status ORDER BY count DESC"
        );

        // Add a color to each status by index
        foreach ($results as $index => $result) {
            $results[$index]->color = $this->colors[$index];
            $results[$index]->value = (int)$result->value;
        }

        return $results;
    }

    /**
     * Get the count of posts per type.
     *
     * @return array
     */
    public function getPostsCountPerType()
    {
        global $wpdb;

        $results = $wpdb->get_results(
            "SELECT
          CONCAT(UCASE(LEFT(post_type, 1)), SUBSTRING(post_type, 2)) AS name,
          CAST(COUNT(*) AS UNSIGNED) AS count,
          ROUND((COUNT(*) / (SELECT COUNT(*) FROM wp_posts) * 100), 2) AS value
       FROM $wpdb->posts
       GROUP BY post_type ORDER BY count DESC"
        );

        // Add a color to each status by index
        foreach ($results as $index => $result) {
            $results[$index]->color = $this->colors[$index];
            $results[$index]->value = (int)$result->value;
        }

        return $results;
    }

    /**
     * Get the count of posts
     *
     * @return int
     */
    public function getPostsCount()
    {
        global $wpdb;

        return $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->posts");
    }

    /**
     * Get the count of posts with a specific status.
     *
     * @param string $status
     * @return int
     */
    protected function getPostsCountWithStatus($status)
    {
        global $wpdb;

        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_status = %s",
                $status
            )
        );
    }

    /**
     * Get the count of posts with a specific type.
     *
     * @param string $type
     * @return int
     */
    protected function getPostsCountWithType($type)
    {
        global $wpdb;

        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = %s",
                $type
            )
        );
    }

    /**
     * Get the list of posts with a specific status.
     *
     */
    protected function getPostsWithStatus($status)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title, post_date, post_author FROM $wpdb->posts WHERE post_status = %s ORDER BY post_date DESC LIMIT 100",
                $status
            )
        );
    }

    /**
     * Get the list of posts with a specific type.
     *
     * @param string $type Can be 'revision'
     */
    protected function getPostsWithType($type)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title, post_date, post_author FROM $wpdb->posts WHERE post_type = %s ORDER BY post_date DESC LIMIT 100",
                $type
            )
        );
    }

    /**
     * Delete posts with a specific status.
     *
     * @param string $status Can be 'auto-draft', 'trash'
     * @return void
     */
    protected function deletePostsWithStatus($status)
    {
        global $wpdb;

        $query = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT ID FROM $wpdb->posts WHERE post_status = %s",
                $status
            )
        );

        if ($query) {
            // if the $status is 'auto-draft' or 'trash', we can use wp_delete_post() to delete the post
            if ($status === 'auto-draft' || $status === 'trash') {
                foreach ($query as $id) {
                    wp_delete_post((int)$id, true);
                }
            }
        }
    }

    /**
     * Delete posts with a specific type.
     *
     * @param string $type Can be 'revision'
     * @return void
     */
    protected function deletePostsWithType($type)
    {
        global $wpdb;

        $query = $wpdb->get_col(
            $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type = %s", $type)
        );

        if ($query) {
            if ($type === 'revision') {
                foreach ($query as $id) {
                    wp_delete_post_revision((int)$id);
                }
            }
        }
    }
}
