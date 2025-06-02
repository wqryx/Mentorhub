<?php

if (!function_exists('setting')) {
    /**
     * Helper function to get or set settings
     *
     * @param string|array|null $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('settings');
        }

        if (is_array($key)) {
            $settings = app('settings');
            foreach ($key as $k => $v) {
                $settings->set($k, $v);
            }
            return $settings;
        }

        return app('settings')->get($key, $default);
    }
}
