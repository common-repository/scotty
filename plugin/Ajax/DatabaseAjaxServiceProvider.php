<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\DatabaseTrait;

class DatabaseAjaxServiceProvider extends AjaxServiceProvider
{

  use DatabaseTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = ['database', 'optimize_table', 'reset_auto_increment', 'get_database_size'];

  /**
   * Get the size of the database in bytes.
   */
  public function get_database_size()
  {
    wp_send_json($this->getDatabaseSize());
  }

  /**
   * Get the list of the database tables.
   */
  public function database()
  {
    wp_send_json($this->getDatabaseTablesStatus());
  }

  /**
   * Optimizes the given tables.
   *
   * @post tables List of the tables to optimize comma separated.
   */
  public function optimize_table()
  {

    [$tables] = $this->useHTTPPost('tables');

    $result = $this->optimizeTables($tables);

    if (is_wp_error($result)) {
      wp_send_json(['error' => $result]);
    }

    wp_send_json(['success' => $result]);
  }

  /**
   * Resets the auto increment of the given tables.
   *
   * @post tables List of the tables to reset comma separated.
   */
  public function reset_auto_increment()
  {
    [$tables] = $this->useHTTPPost('tables');

    $result = $this->resetAutoIncrement($tables);

    if (is_wp_error($result)) {
      wp_send_json(['error' => $result]);
    }

    wp_send_json(['success' => $result]);
  }
}
