<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\ShortcodeTrait;

class ShortcodeAjaxServiceProvider extends AjaxServiceProvider
{
    use ShortcodeTrait;

    /**
     * List of the ajax actions executed only by logged-in users.
     * Here you will use a methods list.
     *
     * @var array
     */
    protected $logged = ['shortcode'];

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
     * Return the list of shortcode.
     *
     * @return void
     */
    public function shortcode()
    {
        $shortcode = $this->getShortcode();
        wp_send_json($shortcode);
    }
}
