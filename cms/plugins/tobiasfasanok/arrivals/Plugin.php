<?php namespace TobiasFasanok\Arrivals;

use System\Classes\PluginBase;
use Backend;

/**
 * Plugin class
 */
class Plugin extends PluginBase
{
    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
    }

    /**
     * registerSettings used by the backend.
     */
    public function registerSettings()
    {
    }

    public function registerNavigation()
    {
        return [
            'arrivals' => [
                'label' => 'Arrivals',
                'url' => Backend::url('tobiasfasanok/arrivals/arrivals'),
                'icon' => 'icon-calendar',
                'permissions' => ['*'],
            ]
        ];
    }
}
