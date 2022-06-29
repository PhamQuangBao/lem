<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\JobRepositoryInterface::class, \App\Repositories\JobRepository::class);
        $this->app->bind(\App\Repositories\ProfileRepositoryInterface::class, \App\Repositories\ProfileRepository::class);
        $this->app->bind(\App\Repositories\UserRepositoryInterface::class, \App\Repositories\UserRepository::class);
        $this->app->bind(\App\Repositories\ProfileForEmailRepositoryInterface::class, \App\Repositories\ProfileForEmailRepository::class);
        $this->app->bind(\App\Repositories\ProfileHistoryRepositoryInterface::class, \App\Repositories\ProfileHistoryRepository::class);
        $this->app->bind(\App\Repositories\ChartRepositoryInterface::class, \App\Repositories\ChartRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
