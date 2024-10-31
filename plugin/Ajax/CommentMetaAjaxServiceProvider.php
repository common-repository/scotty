<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\CommentMetaTrait;

class CommentMetaAjaxServiceProvider extends AjaxServiceProvider
{
  use CommentMetaTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = [
    'orphan_commentmeta',
    'delete_orphan_commentmeta',
    'duplicate_commentmeta'
  ];

  /**
   * Return the data and columns in JSON format.
   *
   * @param array $results
   * @return void
   */
  public function returnColumnsJson($results)
  {
    $data = [];
    if ($results) {
      foreach ($results as $record) {
        $data[] = [
          'id' => $record->meta_id,
          'comment_id' => $record->comment_id,
          'meta_key' => $record->meta_key,
          'meta_value' => $record->meta_value,
        ];
      }
    }

    $json = [
      'data' => $data,
      'columns' => [['accessor' => 'comment_id'], ['accessor' => 'meta_key'], ['accessor' => 'meta_value', 'width' => 300, 'ellipsis' => true, 'noWrap' => true]],
    ];

    wp_send_json($json);
  }

  /**
   * Get the list of orphan commentmeta for Ajax
   *
   * @return void
   */
  public function orphan_commentmeta()
  {
    $results = $this->getOrphanCommentMeta();

    $this->returnColumnsJson($results);
  }

  /**
   * Delete all orphan commentmeta for Ajax
   *
   * @return void
   */
  public function delete_orphan_commentmeta()
  {
    $this->deleteOrphanCommentMeta();
    wp_send_json_success();
  }

  /**
   * Get the list of duplicate commentmeta for Ajax
   *
   * @return void
   */
  public function duplicate_commentmeta()
  {
    $results = $this->getDuplicateCommentMeta();

    $this->returnColumnsJson($results);
  }
}
