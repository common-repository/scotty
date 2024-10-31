<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\UserMetaTrait;

class UserMetaAjaxServiceProvider extends AjaxServiceProvider
{
  use UserMetaTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = [
    'orphan_usermeta',
    'delete_orphan_usermeta',
    'delete_orphan_usermeta_with_id',
    'duplicate_usermeta'
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
          'id' => $record->umeta_id,
          'meta_key' => $record->meta_key,
          'meta_value' => $record->meta_value,
        ];
      }
    }

    $json = [
      'data' => $data,
      'columns' => [['accessor' => 'meta_key'], ['accessor' => 'meta_value', 'width' => 300, 'ellipsis' => true, 'noWrap' => true]],
    ];

    wp_send_json($json);
  }

  /**
   * Return the orphan usermeta.
   *
   * @return void
   */
  public function orphan_usermeta()
  {
    $results = $this->getOrphanUserMeta();
    $this->returnColumnsJson($results);
  }

  public function delete_orphan_usermeta()
  {
    $this->deleteOrphanUserMeta();
    wp_send_json_success();
  }

  /**
   * Delete a usermeta with the given ID.
   *
   * @return void
   */
  public function delete_orphan_usermeta_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    $this->deleteUserMetaWithId($id);
    wp_send_json_success();
  }

  /**
   * Get the count of duplicate usermeta for Ajax
   *
   * @return void
   */
  public function get_duplicate_usermeta_count()
  {
    return $this->getDuplicateUserMetaCount();
  }

  /**
   * Get the list of duplicate usermeta for Ajax
   *
   * @return void
   */
  public function duplicate_usermeta()
  {
    $results = $this->getDuplicateUserMeta();
    $this->returnColumnsJson($results);
  }
}
