<?php


/*
|--------------------------------------------------------------------------
| Plugin Menus routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the menu routes for a plugin.
| In this context, the route are the menu link.
|
*/

$views = [
  'trash' => __('Trash', 'scotty'),
  'duplicates' => __('Duplicates', 'scotty'),
  'database' => __('Database', 'scotty'),
  'cron' => __('Cron', 'scotty'),
  'shortcode' => __('Shortcode', 'scotty'),
  'wordpress' => __('WordPress', 'scotty'),
];

$items = [];

$items[] =  [
  'page_title' => __('Overview', 'scotty'),
  'menu_title' => __('Overview', 'scotty'),
  'capability' => 'read',

  'route' => [
    'load' => 'Dashboard\DashboardController@load',
    'get' => 'Dashboard\DashboardController@index',
  ],
];

foreach ($views as $key => $view) {
  $items[$key] = [
    'page_title' => $view,
    'menu_title' => $view,
    'capability' => 'read',

    'route' => [
      'load' => 'Dashboard\DashboardController@load',
      'get' => 'Dashboard\DashboardController@index',
    ],
  ];
}

return [
  'scotty_slug_menu' => [
    'page_title' => 'Scotty',
    'menu_title' => 'Scotty',
    'capability' => 'read',
    'icon' => 'logo-menu.png',
    'items' =>    $items,
  ],
];
