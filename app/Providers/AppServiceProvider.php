<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // запретить отложенную загрузку
        Model::preventLazyLoading(! $this->app->isProduction());

        // генерировать исключение при попытке назначения неподлежащего назначению атрибута
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());

    }
}
