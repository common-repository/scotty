<?php

namespace NSScottyPlugin\Providers;

if (!defined('ABSPATH')) {
    exit;
}

use NSScottyPlugin\Settings\WordPress\Admin\Appearance;
use NSScottyPlugin\Settings\WordPress\Admin\Menu;
use NSScottyPlugin\Settings\WordPress\General\ExternalAccess;
use NSScottyPlugin\Settings\WordPress\General\Security;
use NSScottyPlugin\Settings\WordPress\Reading\Theme;
use NSScottyPlugin\Settings\WordPress\Writing\Posts as WritingPosts;
use NSScottyPlugin\WPBones\Support\ServiceProvider;

class NSScottyPluginProvider extends ServiceProvider
{

    public function register()
    {
        Security::boot();
        ExternalAccess::boot();
        Appearance::boot();
        Menu::boot();
        WritingPosts::boot();
        Theme::boot();
    }
}
