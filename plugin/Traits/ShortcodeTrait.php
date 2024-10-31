<?php

namespace NSScottyPlugin\Traits;

use NSScottyPlugin\Traits\MantineUITrait;

trait ShortcodeTrait
{
  use MantineUITrait;

  /**
   * Get the count of shortcode.
   *
   * @return int
   */
  public function getShortcodeCount()
  {
    global $shortcode_tags;
    return count($shortcode_tags);
  }

  /**
   * Get the list of shortcodes.
   *
   * @return array
   */
  public function getShortcode()
  {
    global $shortcode_tags;

    $shortcodes = [];

    foreach ($shortcode_tags as $shortcode => $function) {

      $item = [
        'id' => $shortcode,
        'name' => $shortcode,
        'callable' => false,
        'callback' => ''
      ];

      // Extract addition info and callback
      if (is_string($function)) {
        $item['callback'] = $function;
        $item['callable'] = is_callable($function, true);
      }
      // Object class method
      elseif (is_array($function) && is_object($function[0]) && is_string($function[1])) {
        $item['callback'] = sprintf('%s::%s()', get_class($function[0]), $function[1]);
        $item['callable'] = is_callable($function, true);
      }
      // Object string class method
      elseif (is_array($function) && is_string($function[0]) && is_string($function[1])) {
        $item['callback'] = sprintf('%s::%s()', $function[0], $function[1]);
        $item['callable'] = is_callable($function, true);
      }


      $shortcodes[] = $item;
    }

    return [...$shortcodes];
  }
}
