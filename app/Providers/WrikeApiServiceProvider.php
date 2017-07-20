<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WrikeApiServiceProvider extends ServiceProvider {
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->bind('WrikeApiFacade', function(){
            return new App\Offer;
        });
    }
}