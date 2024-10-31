<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\OptionsTrait;

class OptionsAjaxServiceProvider extends AjaxServiceProvider
{
  use OptionsTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = [
    'transient_options',
    'delete_transient_options',
    'delete_transient_options_with_id',
    'reset_options'
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
          'id' => $record->option_id,
          'name' => $record->option_name,
          'value' => $record->option_value,
        ];
      }
    }

    $json = [
      'data' => $data,
      'columns' => [['accessor' => 'name'], ['accessor' => 'value', 'width' => 300, 'ellipsis' => true, 'noWrap' => true]],
    ];

    wp_send_json($json);
  }


  /**
   * Get the list of transient options for Ajax
   *
   * @return void
   */
  public function transient_options()
  {
    $results = $this->getTransientOptions();

    $this->returnColumnsJson($results);
  }

  /**
   * Delete all transient options
   *
   * @return void
   */
  public function delete_transient_options()
  {
    $this->deleteTransientOptions();
    wp_send_json_success();
  }

  /**
   * Delete a transient option by id
   *
   * @return void
   */
  public function delete_transient_options_with_id()
  {
    [$id] = $this->useHTTPPost('id');
    $this->deleteTransientOption($id);
    wp_send_json_success();
  }

  /**
   * Reset all options
   *
   * @return void
   */
  public function reset_options()
  {
    $this->resetOptions();
    wp_send_json_success();
  }
}
