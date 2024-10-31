<?php

namespace NSScottyPlugin\Traits;

use NSScottyPlugin\Traits\MantineUITrait;

trait CommentsTrait
{
  use MantineUITrait;

  /**
   * Get the count of comments per approved.
   *
   * @return array
   */
  public function getCommentsCountPerApproved()
  {
    global $wpdb;

    $unapproved = __('Unapproved', 'scotty');
    $approved = __('Approved', 'scotty');

    $results = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT
          CASE
              WHEN comment_approved = %s THEN '$unapproved'
              WHEN comment_approved = %s THEN '$approved'
              ELSE CONCAT(UCASE(LEFT(comment_approved, 1)), SUBSTRING(comment_approved, 2))
          END AS name,
          COUNT(*) AS count,
          ROUND((COUNT(*) / (SELECT COUNT(*) FROM $wpdb->comments) * 100), 2) AS value
      FROM $wpdb->comments
      GROUP BY name ORDER BY name ASC;",
        '0',
        '1'
      )
    );


    // Add a color to each status by index
    foreach ($results as $index => $result) {
      $results[$index]->color = $this->colors[$index];
      $results[$index]->value = (int) $result->value;
    }

    return $results;
  }

  /**
   * Return the count of comments.
   *
   * @return int
   */
  public function getCommentsCount()
  {
    global $wpdb;

    return $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments");
  }

  /**
   * Return the count of unapproved comments.
   *
   * @return int
   */
  public function getUnapprovedCommentsCount()
  {
    global $wpdb;

    return $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = %s",
        '0'
      )
    );
  }

  /**
   * Return the count of approved comments.
   *
   * @return int
   */
  public function getApprovedCommentsCount()
  {
    global $wpdb;

    return $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = %s",
        '1'
      )
    );
  }

  /**
   * Return the unapproved comments.
   *
   * @return array
   */
  public function getUnapprovedComments()
  {
    global $wpdb;

    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT comment_ID, comment_post_ID, comment_author, comment_author_email, comment_author_url, comment_date, comment_content FROM $wpdb->comments WHERE comment_approved = %s",
        '0'
      )
    );
  }

  /**
   * Return the count of spam comments.
   *
   * @return int
   */
  public function getSpamCommentsCount()
  {
    global $wpdb;

    return $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_approved = %s",
        'spam'
      )
    );
  }

  /**
   * Return the spam comments.
   *
   * @return array
   */
  public function getSpamComments()
  {
    global $wpdb;

    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT comment_ID, comment_post_ID, comment_author, comment_author_email, comment_author_url, comment_date, comment_content FROM $wpdb->comments WHERE comment_approved = %s",
        'spam'
      )
    );
  }

  /**
   * Return the count of trash comments.
   *
   * @return int
   */
  public function getDeletedCommentsCount()
  {
    global $wpdb;

    return $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE (comment_approved = %s OR comment_approved = %s)",
        'trash',
        'post-trashed'
      )
    );
  }

  /**
   * Return the trash comments.
   *
   * @return array
   */
  public function getDeletedComments()
  {
    global $wpdb;

    return $wpdb->get_results(
      $wpdb->prepare(
        "SELECT comment_ID, comment_post_ID, comment_author, comment_author_email, comment_author_url, comment_date, comment_content FROM $wpdb->comments WHERE (comment_approved = %s OR comment_approved = %s)",
        'trash',
        'post-trashed'
      )
    );
  }
}
