<?php

namespace Localization;

use Localization\Detectors\Browser;
use Localization\Detectors\Request;

class Locale {

    protected $fallBack;

    protected $locales = [];

    protected $detectors = [
        Request::class,
        Browser::class,
    ];

    protected $default;

    protected $currentLocale;

    protected $request;


    public function addLocales(array $locales = array()) {
        $this->locales = array_merge(array_flip($locales), $this->getLocales());

        return $this;
    }

    public function setLocales(array $locales = array()) {
        $this->locales = array_flip($locales);

        return $this;
    }

    public function getLocales() {
        return $this->locales;
    }

    public function getLocale($locale) {
        return isset($this->locales[$locale]) ? $locale : null;
    }


    public function setDefault($locale) {
        $this->default = $locale;

        return $this;
    }

    public function getDefault() {
        return $this->default;
    }

    public function setRequest($request) {
        $this->request = $request;

        return $this;
    }

    public function getRequest() {
        return $this->request;
    }


    public function isValid($locale) {
        return $this->getLocale($locale);
    }


    public function setCurrentLocale($locale) {
        if(! $this->isValid($locale))
            throw new LocaleException(_('Unsupported locale'));

        $this->currentLocale = $locale;

        return $this;
    }

    public function getActiveLocale() {
        return $this->currentLocale;
    }


    public function setFallBack($locale) {
        $this->fallBack = $locale;

        return $this;
    }

    public function getFallBack() {
        return $this->fallBack;
    }


    public function detect(\Closure $onSuccess = null) {
        $detectors = $this->getDetectors();
        $locale    = null;

        foreach($detectors as $detector) {
            if(! class_exists($detector)) continue;

            $detector = new $detector;

            if( ! $detector instanceof Detectable ) continue;

            if( $locale = $detector->detect(
                $this->getRequest()
            ) )
                break;
        }

        if( is_null($locale) )
            $locale = $this->getDefault();

        if( $onSuccess && $locale )
            $onSuccess($locale);

        $this->setCurrentLocale($locale);

        return $this;
    }

    public function setDetectors(array $detectors = array()) {
        $this->detectors = $detectors;

        return $this;
    }

    public function getDetectors() {
        return $this->detectors;
    }

}