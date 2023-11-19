<?php namespace TobiasFasanok\Users;

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
            'users' => [
                'label' => 'Users',
                'url' => Backend::url('tobiasfasanok/users/users'),
                'icon' => 'icon-child',
                'permissions' => ['*'],
            ]
        ];
    }
}
