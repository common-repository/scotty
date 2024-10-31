<?php

namespace NSScottyPlugin\Traits;

use WP_Debug_Data;

trait DiskTrait
{
    use MantineUITrait;

    /**
     * Get the disk usage.
     *
     * @return array
     */
    public function getDiskUsage()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        $percent = round($used / $total * 100, 2);

        return [
            'total' => $total,
            'free' => $free,
            'used' => $used,
            'percent' => $percent
        ];
    }

    /**
     * Get the list of the files and directories in the given path.
     *
     * @return array
     */
    public function getWordPressSizes()
    {
        if (!class_exists('WP_Debug_Data')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
        }

        // check if the transient exists
        if (false !== ($sizes = get_transient('scotty_debug_sizes'))) {
            return $sizes;
        }

        $sizes = WP_Debug_Data::get_sizes();

        // store for 10 minutes in the WordPress transient
        set_transient('scotty_debug_sizes', $sizes, 600);

        return $sizes;
    }
}
