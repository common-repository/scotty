<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\PostMetaTrait;

class PostMetaAjaxServiceProvider extends AjaxServiceProvider
{
  use PostMetaTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = [
    'orphan_postmeta',
    'delete_orphan_postmeta',
    'delete_orphan_postmeta_with_id',
    'delete_duplicate_postmeta_with_id',
    'duplicate_postmeta',
    'delete_duplicate_postmeta',
  ];

  /**
   * Return the postmeta columns in JSON format.
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
          'id' => $record->id,
          'post_id' => $record->post_id,
          'meta_key' => $record->meta_key,
          'meta_value' => $record->meta_value,
        ];
      }
    }

    $json = [
      'data' => $data,
      'columns' => [['accessor' => 'post_id'], ['accessor' => 'meta_key'], ['accessor' => 'meta_value']],
    ];

    wp_send_json($json);
  }

  /**
   * Get the list of orphan postmeta for Ajax
   *
   * @return void
   */
  public function orphan_postmeta()
  {
    $this->returnColumnsJson($this->getOrphanPostMeta());
  }

  /**
   * Delete the orphan postmeta for Ajax
   *
   * @return void
   */
  public function delete_orphan_postmeta()
  {
    $this->deleteOrphanPostMeta();
    wp_send_json_success();
  }

  /**
   * Delete the orphan postmeta with a specific ID for Ajax
   *
   * @return void
   */
  public function delete_orphan_postmeta_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    $this->deletePostMetaWithId($id);
    wp_send_json_success();
  }
  // alias
  public function delete_duplicate_postmeta_with_id()
  {
    $this->delete_orphan_postmeta_with_id();
  }

  /**
   * Duplicate the postmeta for Ajax
   *
   * @return void
   */
  public function duplicate_postmeta()
  {
    $this->returnColumnsJson($this->getDuplicatePostMeta());
  }

  /**
   * Delete the duplicate postmeta for Ajax
   *
   * @return void
   */
  public function delete_duplicate_postmeta()
  {
    $this->deleteDuplicatePostMeta();
    wp_send_json_success();
  }
}
