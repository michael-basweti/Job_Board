<?php

namespace App\Providers;
use Laravel\Lumen\Routing\UrlGenerator;
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
        $this->app->register(\Tymon\JWTAuth\Providers\LumenServiceProvider::class);
    }
    public function boot(UrlGenerator $url){

        if(env('REDIRECT_HTTPS'))
         {
           $url->forceScheme('https');
         }


   }

}
