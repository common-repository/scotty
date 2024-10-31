<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\TermMetaTrait;

class TermMetaAjaxServiceProvider extends AjaxServiceProvider
{
  use TermMetaTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = [
    'orphan_termmeta',
    'delete_orphan_termmeta_with_id',
    'delete_orphan_termmeta',
    'duplicate_termmeta',
    'delete_duplicate_termmeta',
    'delete_duplicate_termmeta_with_id'
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
          'term_id' => $record->term_id,
          'meta_key' => $record->meta_key,
          'meta_value' => $record->meta_value,
        ];
      }
    }

    $json = [
      'data' => $data,
      'columns' => [
        ['accessor' => 'term_id'],
        ['accessor' => 'meta_key'],
        [
          'accessor' => 'meta_value',
          'width' => 300,
          'ellipsis' => true,
          'noWrap' => true,
        ],
      ],
    ];

    wp_send_json($json);
  }

  /**
   * Get the orphan term meta.
   *
   * @return void
   */
  public function orphan_termmeta()
  {
    $results = $this->getOrphanTermMeta();
    $this->returnColumnsJson($results);
  }

  /**
   * Delete a term meta.
   *
   * @return void
   */
  public function delete_orphan_termmeta_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    $this->deleteTermMetaWithId($id);
    wp_send_json_success();
  }

  public function delete_orphan_termmeta()
  {
    $results = $this->deleteOrphanTermMeta();
    wp_send_json_success();
  }

  /**
   * Get the duplicate term meta.
   *
   * @return void
   */
  public function duplicate_termmeta()
  {
    $results = $this->getDuplicateTermMeta();
    $this->returnColumnsJson($results);
  }

  /**
   * Delete duplicate term meta.
   *
   * @return void
   */
  public function delete_duplicate_termmeta()
  {
    $results = $this->deleteDuplicateTermMeta();
    wp_send_json_success();
  }

  /**
   * Delete a term meta.
   *
   * @return void
   */
  public function delete_duplicate_termmeta_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    $this->deleteTermMetaWithId($id);
    wp_send_json_success();
  }
}
