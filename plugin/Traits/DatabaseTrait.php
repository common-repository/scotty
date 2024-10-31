<?php

namespace NSScottyPlugin\Traits;

use NSScottyPlugin\Traits\MantineUITrait;

trait DatabaseTrait
{
  use MantineUITrait;

  public function resetAutoIncrement($tables)
  {
    global $wpdb;

    $tableNames = explode(',', $tables);

    foreach ($tableNames as $tableName) {
      $result = $wpdb->query($wpdb->prepare('ALTER TABLE %i AUTO_INCREMENT = 1', $tableName));
    }

    return $result;
  }

  /**
   * Optimize the database tables.
   *
   * @return array
   */
  public function optimizeTables($tables)
  {
    global $wpdb;

    $result = $wpdb->query($wpdb->prepare('OPTIMIZE TABLE %1s', $tables));

    return $result;
  }

  /**
   * Get the list of the database tables.
   *
   * @return array
   */
  public function getDatabaseTablesStatus()
  {
    global $wpdb;

    $tables = $wpdb->get_results($wpdb->prepare('SHOW TABLE STATUS FROM %i', DB_NAME));

    return $tables;
  }

  /**
   * Get the size of the database in bytes.
   *
   * @return int
   */
  public  function getDatabaseSize()
  {
    global $wpdb;

    $size = 0;
    $rows = $wpdb->get_results('SHOW TABLE STATUS', ARRAY_A);

    if ($wpdb->num_rows > 0) {
      foreach ($rows as $row) {
        $size += $row['Data_length'] + $row['Index_length'];
      }
    }

    return (int) $size;
  }
}
