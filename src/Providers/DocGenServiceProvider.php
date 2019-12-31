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
    }

}
