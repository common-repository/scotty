<?php

namespace NSScottyPlugin\Traits;

trait CronTrait
{
    use MantineUITrait;

    /**
     * Get the number of cron jobs.
     *
     * @return int
     */
    public function getCronCount()
    {
        $crons = _get_cron_array();
        return count($crons);
    }

    /**
     * Get the cron jobs.
     *
     * @return array
     */
    public function getCron()
    {
        $items = [];
        $crons = _get_cron_array();
        $schedules = wp_get_schedules();

        //$disabled = get_site_option('wpxcm_disabled_cron', []);

        foreach ($crons as $timestamp => $cronhooks) {
            foreach ((array)$cronhooks as $hook => $events) {
                foreach ((array)$events as $sig => $event) {
                    $item = json_decode(
                        wp_json_encode([
                            'id' => $hook,
                            'hook_name' => $hook,
                            'signature' => $sig,
                            'next_run' => date_i18n(
                                'Y-m-d H:i:s',
                                wp_next_scheduled($hook),
                                true,
                                'UTC'
                            ),
                            //'status' => in_array($hook, $disabled) ? 'off' : 'on',
                        ])
                    );

                    if ($event['schedule']) {
                        $item->schedule = $schedules[$event['schedule']]['display'];
                    } // Single event
                    else {
                        $item->schedule = __('One-time', 'scotty');
                    }
                    $items[] = $item;
                }
            }
        }

        return [...$items];
    }

    /**
     * Execute the cron job.
     *
     * @return void
     */
    public function execute($cron)
    {

        $object = json_decode(stripslashes($cron));

        $notFound = true;

        $hookname = $object->hook_name;
        $signature = $object->signature;

        if (is_null($hookname) || is_null($signature)) {
            $response = [
                'status' => 'error',
                'description' => __("Error!!\n\nParams mismatch!", 'scotty')
            ];
            wp_send_json_error($response);
        }

        // Loop in cron
        foreach (_get_cron_array() as $timestamp => $cronhooks) {

            if (isset($cronhooks[$hookname][$signature])) {
                $notFound = false;
                $args = $cronhooks[$hookname][$signature]['args'];

                wp_clear_scheduled_hook($hookname, $args);

                delete_transient('doing_cron');
                wp_schedule_single_event(time() - 1, $hookname, $args);
                spawn_cron();
            }
        }

        if ($notFound) {
            $response = [
                'status' => 'error',
                'description' => __("Warning!!\n\nAn error occur when try to get the cron! Cron not found!", 'scotty')
            ];
            wp_send_json_error($response);
        }

        wp_send_json_success();
    }
}
