<?php

namespace App\Providers;
use App\Contracts\ISezrisService;
use App\Services\ApplicationService;
use App\Services\UploadService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //access cco only
        Gate::define('access-cco', function ($user) {
        return optional($user->details)->position_id === 37 &&
               optional($user->details)->department_id === 12 &&
               optional($user->details)->role_id === 2 &&
               optional($user->details)->permission_id === 2;
          });
          //osac access only
          Gate::define('access-osac', function ($user) {
        return optional($user->details)->position_id === 36 &&
            optional($user->details)->department_id === 12 &&
            optional($user->details)->role_id === 2 &&
            optional($user->details)->permission_id === 2;
          });
          //locator access
          Gate::define('access-locator', function ($user) {
        return  optional($user->details)->role_id == 3 &&
            optional($user->details)->permission_id == 2;
          });
          Gate::define('access-sezadManager', function($user){
            return optional($user->details)->position_id == 50  &&
                 optional($user->details)->department_id === 12 &&
                 optional($user->details)->user_function_id === 5 &&
                 optional($user->details)->role_id === 2 &&
                 optional($user->details)->permission_id === 1;
          });
          Gate:: define('access-finance', function($user){
             return optional($user->details)->position_id == null  &&
                 optional($user->details)->department_id === 10 &&
                 optional($user->details)->user_function_id === null &&
                 optional($user->details)->role_id === 2 &&
                 optional($user->details)->permission_id === 2;
          });

        
    }
}
