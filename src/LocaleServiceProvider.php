<?php

namespace Localization;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Yaml;

class LocaleServiceProvider extends Serviceprovider {

    public function boot(Request $request) {
        $this->loadConfiguration();

        $this->app->bind('laravel-locale', function() use($request) {
            return (new Locale)
                ->setRequest($request)
                ->setDetectors(config('laravel-locale.detectors'))
                ->setLocales(config('laravel-locale.locales'))
                ->setDefault(config('laravel-locale.fallback'))
                ->detect(function($locale) {

                });
        });
    }

    public function register() {
        $this->app->singleton('locale-formatter', Formatter::class);
    }

    #@todo adding helper which will do all that  .
    protected function loadConfiguration() {
        $array = Yaml::parse(file_get_contents(
            __DIR__ . '/../configuration/general.yaml'
        ));

        $config = $this->app['config']->get('laravel-locale', []);

        $this->app['config']->set('laravel-locale', array_merge($array, $config));

        return $this;
    }
}

