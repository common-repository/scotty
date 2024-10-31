<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\PostsTrait;

class PostsAjaxServiceProvider extends AjaxServiceProvider
{
  use PostsTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = [
    'revisions',
    'delete_revisions',
    'delete_revisions_with_id',
    'auto_draft',
    'delete_auto_draft',
    'delete_auto_draft_with_id',
    'deleted_posts',
    'delete_deleted_posts',
    'delete_deleted_posts_with_id',
    'delete_post_with_id'
  ];

  /**
   * Return the data and columns in JSON format.
   *
   * @param array $results
   * @return void
   */
  private function returnColumnsJson($results)
  {
    $data = [];
    if ($results) {
      foreach ($results as $record) {
        $data[] = [
          'id' => $record->ID,
          'title' => $record->post_title,
          'date' => $record->post_date,
          'author' => get_the_author_meta('display_name', $record->post_author),
        ];
      }
    }

    $json = [
      'data' => $data,
      'columns' => [['accessor' => 'title'], ['accessor' => 'date'], ['accessor' => 'author']],
    ];

    wp_send_json($json);
  }

  /*
  |--------------------------------------------------------------------------
  | Ajax Methods
  |--------------------------------------------------------------------------
  */

  public function revisions()
  {
    $results = $this->getPostsWithType('revision');

    $this->returnColumnsJson($results);
  }

  public function delete_revisions()
  {
    $this->deletePostsWithType('revision');

    wp_send_json_success();
  }

  public function delete_revisions_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    wp_delete_post_revision((int) $id);

    wp_send_json_success();
  }

  public function auto_draft()
  {
    $result = $this->getPostsWithStatus('auto-draft');
    $this->returnColumnsJson($result);
  }

  public function delete_auto_draft()
  {
    $this->deletePostsWithStatus('auto-draft');

    wp_send_json_success();
  }

  public function delete_auto_draft_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    wp_delete_post((int) $id, true);

    wp_send_json_success();
  }

  public function deleted_posts()
  {
    $results = $this->getPostsWithStatus('trash');
    $this->returnColumnsJson($results);
  }


  public function delete_deleted_posts()
  {
    $this->deletePostsWithStatus('trash');
    wp_send_json_success();
  }

  public function delete_deleted_posts_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    wp_delete_post((int) $id, true);

    wp_send_json_success();
  }

  public function delete_post_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    wp_delete_post((int) $id, true);

    wp_send_json_success();
  }
}
