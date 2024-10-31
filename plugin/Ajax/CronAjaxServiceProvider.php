<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\CronTrait;

class CronAjaxServiceProvider extends AjaxServiceProvider
{
  use CronTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = ['cron', 'execute_cron'];

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
          'id' => $record['hook_name'],
          'name' => $record['hook_name'],
          'signature' => $record['signature'],
          'next_run' => $record['next_run'],
          'schedule' => $record['schedule'],
          //'status' => $record['status'],
        ];
      }
    }

    $json = [
      'data' => $data,
      'columns' => [['accessor' => 'name'], ['accessor' => 'signature']],
    ];

    wp_send_json($json);
  }

  /**
   * Return the list of cron jobs.
   *
   * @return void
   */
  public function cron()
  {
    $crons = $this->getCron();
    wp_send_json($crons);
  }

  /**
   * Execute the cron job.
   *
   * @return void
   */
  public function execute_cron()
  {
    [$cron] = $this->useHTTPPost('cron');
    $this->execute($cron);
    wp_send_json_success();
  }
}
