<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;

use Illuminate\Support\ServiceProvider;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        //
      //  echo  session('LAST_ACTIVITY');die;
        $this->app->booted(function() {
                config([
                    // Override session lifetime with data from user record.
                    'session.lifetime' => 5,
                ]);
         });
  
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register User Repository
        $this->app->bind(
            'App\Repositories\IUserRepository',
            'App\Repositories\UserRepository'
        );
        // Register Plan Repository
        $this->app->bind(
            'App\Repositories\IPlanRepository',
            'App\Repositories\PlanRepository'
        );
        // Register Video Repository
        $this->app->bind(
            'App\Repositories\IVideoRepository',
            'App\Repositories\VideoRepository'
        );
         // Register Shared Videos Repository
         $this->app->bind(
            'App\Repositories\ISharedRepository',
            'App\Repositories\SharedRepository'
        );
        // Register PayementApiRepository Repository // Borgun
        $this->app->bind(
            'App\Repositories\IPayementApiRepository',
            'App\Repositories\PayementApiRepository'
        );
        
    }
}
