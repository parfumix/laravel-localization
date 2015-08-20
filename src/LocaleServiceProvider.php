<?php

namespace Localization;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Flysap\Support;

class LocaleServiceProvider extends Serviceprovider {

    public function boot(Request $request) {
        $this->publishes([
            __DIR__.'/../configuration' => config_path('yaml/localization'),
        ]);

        $this->loadConfiguration();

        $this->app->bind('laravel-locale', function() use($request) {
            return (new Locale)
                ->setRequest($request)
                ->setDetectors(config('laravel-locale.detectors'))
                ->setLocales(config('laravel-locale.locales'))
                ->setDefault(config('laravel-locale.fallback'))
                ->detect(function($locale) {
                    app()->setLocale(
                        $locale
                    );
                });
        });
    }

    public function register() {
        $this->app->singleton('locale-formatter', Formatter::class);
    }

    protected function loadConfiguration() {
        Support\set_config_from_yaml(
            __DIR__ . '/../configuration/general.yaml' , 'laravel-locale'
        );

        return $this;
    }
}

