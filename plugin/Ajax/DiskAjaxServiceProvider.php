<?php

namespace NSScottyPlugin\Ajax;

use NSScottyPlugin\Ajax\AjaxServiceProvider;
use NSScottyPlugin\Traits\DiskTrait;

class DiskAjaxServiceProvider extends AjaxServiceProvider
{

  use DiskTrait;

  /**
   * List of the ajax actions executed only by logged-in users.
   * Here you will use a methods list.
   *
   * @var array
   */
  protected $logged = ['get_disk_usage', 'get_wordpress_sizes'];

  /**
   * Get the disk usage.
   */
  public function get_disk_usage()
  {
    wp_send_json($this->getDiskUsage());
  }

  /**
   * Get the list of the files and directories in the given path.
   */
  public function get_wordpress_sizes()
  {
    wp_send_json($this->getWordPressSizes());
  }
}
