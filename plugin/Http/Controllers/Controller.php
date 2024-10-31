<?php

namespace NSScottyPlugin\Http\Controllers;

use NSScottyPlugin\WPBones\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{

  public function load()
  {
    add_filter('admin_footer_text', '__return_false');
    add_filter('update_footer', '__return_false', 99);

    remove_all_actions('admin_notices');
    remove_all_actions('all_admin_notices');
  }
}
