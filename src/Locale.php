<?php

namespace Localization;

class Locale {

    protected $fallBack;

    protected $locales = [];

    protected $detectors = [];

    protected $default;

    protected $currentLocale;

    protected $request;


    /**
     * Add new locales .
     *
     * @param array $locales
     * @return $this
     */
    public function addLocales(array $locales = array()) {
        $this->locales = array_merge(array_flip($locales), $this->getLocales());

        return $this;
    }

    /**
     * Set locales .
     *
     * @param array $locales
     * @return $this
     */
    public function setLocales(array $locales = array()) {
        $this->locales = array_flip($locales);

        return $this;
    }

    /**
     * Get locales .
     *
     * @return array
     */
    public function getLocales() {
        return $this->locales;
    }

    /**
     * Get locale .
     *
     * @param $locale
     * @return null
     */
    public function getLocale($locale) {
        return isset($this->locales[$locale]) ? $locale : null;
    }


    /**
     * Set default locale .
     *
     * @param $locale
     * @return $this
     */
    public function setDefault($locale) {
        $this->default = $locale;

        return $this;
    }

    /**
     * Get default locale .
     *
     * @return mixed
     */
    public function getDefault() {
        return $this->default;
    }


    /**
     * Set http request .
     *
     * @param $request
     * @return $this
     */
    public function setRequest($request) {
        $this->request = $request;

        return $this;
    }

    /**
     * Get http request .
     *
     * @return mixed
     */
    public function getRequest() {
        return $this->request;
    }


    /**
     * Check if is valid locale .
     *
     * @param $locale
     * @return null
     */
    public function isValid($locale) {
        return $this->getLocale($locale);
    }


    /**
     * Set current locale .
     *
     * @param $locale
     * @return $this
     * @throws LocaleException
     */
    public function setCurrentLocale($locale) {
        if(! $this->isValid($locale))
            throw new LocaleException(_('Unsupported locale'));

        $this->currentLocale = $locale;

        return $this;
    }

    /**
     * Get current locale .
     *
     * @return mixed
     */
    public function getCurrentLocale() {
        return $this->currentLocale;
    }

    /**
     * Set fallback .
     *
     * @param $locale
     * @return $this
     */
    public function setFallBack($locale) {
        $this->fallBack = $locale;

        return $this;
    }

    /**
     * Get fallback .
     *
     * @return mixed
     */
    public function getFallBack() {
        return $this->fallBack;
    }


    /**
     * Detect locale .
     *
     * @param callable $onSuccess
     * @return $this
     * @throws LocaleException
     */
    public function detect(\Closure $onSuccess = null) {
        $detectors = $this->getDetectors();
        $locale    = null;

        foreach($detectors as $detector) {
            if(! class_exists($detector))
                continue;

            $detector = new $detector;

            if( ! $detector instanceof Detectable )
                continue;

            if( $locale = $detector->detect(
                $this->getRequest()
            ) ) {
                if( $this->isValid($locale) )
                    break;
            }
        }

        if( is_null($locale) )
            $locale = $this->getDefault();

        if( $onSuccess && $locale )
            $onSuccess($locale);

        $this->setCurrentLocale($locale);

        return $this;
    }

    /**
     * Set detectors .
     *
     * @param array $detectors
     * @return $this
     */
    public function setDetectors(array $detectors = array()) {
        $this->detectors = $detectors;

        return $this;
    }

    /**
     * Get detectors .
     *
     * @return array
     */
    public function getDetectors() {
        return $this->detectors;
    }

}