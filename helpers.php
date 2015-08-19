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
    return app('laravel-locale')
        ->getCurrentLocale();
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