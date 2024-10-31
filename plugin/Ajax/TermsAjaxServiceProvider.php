<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\TermsTrait;

class TermsAjaxServiceProvider extends AjaxServiceProvider
{
  use TermsTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = [
    'unused_terms',
    'delete_unused_terms',
    'delete_unused_terms_with_id'
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
          'id' => $record->term_id,
          'name' => $record->name,
          'slug' => $record->slug,
        ];
      }
    }

    $json = [
      'data' => $data,
      'columns' => [['accessor' => 'name'], ['accessor' => 'slug']],
    ];

    wp_send_json($json);
  }

  public function unused_terms()
  {
    $unused_terms = $this->getUnusedTerms();
    $this->returnColumnsJson($unused_terms);
  }

  public function delete_unused_terms()
  {
    $deleted_terms = $this->deleteUnusedTerms();
    wp_send_json_success();
  }

  public function delete_unused_terms_with_id()
  {
    [$term_id] = $this->useHTTPPost('id');
    $deleted_term = $this->deleteUnusedTerm($term_id);
    wp_send_json_success();
  }
}
