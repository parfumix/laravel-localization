<?php

namespace Localization;

use Illuminate\Support\ServiceProvider;

class LocaleServiceProvider extends Serviceprovider {

    public function boot() {
        
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->share('locale', function() {
            return new Locale;
        });
    }

}

