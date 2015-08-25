<?php

namespace Localization;

/**
 * Get all availables locales .
 *
 * @return mixed
 */
function get_locales() {
    return app('laravel-locale')
        ->getLocales();
}

/**
 * Get default locale .
 *
 * @return mixed
 */
function get_default_locale() {
    return app('laravel-locale')
        ->getDefault();
}

/**
 * Get active locale .
 *
 * @return mixed
 */
function get_active_locale() {
    $currentLocale = app('laravel-locale')
        ->getCurrentLocale();

    return $currentLocale;
}

/**
 * Get locale for specific detector .
 *
 * @param $detector
 * @return mixed
 */
function get_detector_locale($detector) {
    $request = app('request');

    $detector = app('laravel-locale')
        ->getDetector($detector);

    return $detector
        ->detect($request);
}

/**
 * Format locale and return formatter instance ..
 *
 * @param $locale
 * @return mixed
 */
function format_locale($locale) {
    return app('locale-formatter')
        ->parse($locale);
}