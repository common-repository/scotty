<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\TermRelationshipsTrait;

class TermRelationshipsAjaxServiceProvider extends AjaxServiceProvider
{
  use TermRelationshipsTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = [];

  /**
   * Return the data and columns in JSON format.
   *
   * @param array $results
   * @return void
   */
  public function return_columns_json($results)
  {
    $data = [];
    if ($results) {
      foreach ($results as $record) {
        $data[] = [
          'id' => $record->object_id,
          'term_taxonomy_id' => $record->term_taxonomy_id,
        ];
      }
    }

    $json = [
      'data' => $data,
      'columns' => [['accessor' => 'term_taxonomy_id']],
    ];

    wp_send_json($json);
  }
}
