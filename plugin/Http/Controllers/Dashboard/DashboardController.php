<?php

namespace NSScottyPlugin\Http\Controllers\Dashboard;

use NSScottyPlugin\Http\Controllers\Controller;

class DashboardController extends Controller
{
  public function index()
  {
    return NSScottyPlugin()
      ->view('dashboard.index')
      ->withInlineStyle('app', '#wpbody-content { padding-bottom: 0; }')
      ->withLocalizeScript('app', 'NSScottyPluginMantine', [
        'nonce' => wp_create_nonce('scotty-mantine'),
        'version' => NSScottyPlugin()->Version,
        'preferences' => NSScottyPlugin()->options->toArray(),
        'health' => wp_create_nonce('wp_rest'),
      ])
      ->withAdminAppsScript('app', true);
  }
}
