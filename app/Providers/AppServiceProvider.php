<?php

namespace App\Providers;
use App\Contracts\ISezrisService;
use App\Services\ApplicationService;
use App\Services\UploadService;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ApplicationService::class, ApplicationService::class);
    $this->app->bind(UploadService::class, UploadService::class);

    // You can also bind the default interface if you want
    $this->app->bind(ISezrisService::class, ApplicationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       
    }
}
