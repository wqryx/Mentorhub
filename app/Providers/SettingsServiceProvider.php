<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('settings', function ($app) {
            return new class {
                protected $settings = null;

                /**
                 * Get a setting value
                 *
                 * @param string $key
                 * @param mixed $default
                 * @return mixed
                 */
                public function get($key, $default = null)
                {
                    // Use cache to improve performance
                    return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
                        return Setting::get($key, $default);
                    });
                }

                /**
                 * Set a setting value
                 *
                 * @param string $key
                 * @param mixed $value
                 * @param string|null $description
                 * @param string $group
                 * @return mixed
                 */
                public function set($key, $value, $description = null, $group = 'general')
                {
                    $setting = Setting::set($key, $value, $description, $group);
                    Cache::forget("setting.{$key}");
                    return $setting;
                }

                /**
                 * Get all settings grouped
                 *
                 * @return \Illuminate\Support\Collection
                 */
                public function all()
                {
                    return Cache::rememberForever('settings.all', function () {
                        return Setting::getAllGrouped();
                    });
                }

                /**
                 * Clear settings cache
                 *
                 * @return void
                 */
                public function clearCache()
                {
                    Cache::forget('settings.all');
                    // Clear individual setting caches
                    $settings = Setting::all();
                    foreach ($settings as $setting) {
                        Cache::forget("setting.{$setting->key}");
                    }
                }
            };
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register a blade directive for settings
        \Illuminate\Support\Facades\Blade::directive('setting', function ($expression) {
            return "<?php echo app('settings')->get({$expression}); ?>";
        });
        
        // Register the helper function
        if (!function_exists('setting')) {
            function setting($key = null, $default = null) {
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
    }
}
