<?php


namespace Waxwink\DocGen\Providers;


use Illuminate\Support\ServiceProvider;

class DocGenServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadViewsFrom(__DIR__. '/../../resources/views/', 'docgen');

        $this->publishes([
            __DIR__.'/../../assets' => public_path('vendor/waxwink/docgen'),
        ], 'public');
    }

}
