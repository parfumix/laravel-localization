<?php

namespace Localization\Detectors;

use Localization\Detectable;
use Localization;

class System implements Detectable {

    public function detect($request) {
        foreach (explode('/', setlocale(LC_ALL, 0)) as $locale) {
            if ('C' !== $locale) {
                break;
            }
        }

        if ('C' === $locale) {
            return null;
        }

        $locale = str_replace(
            '_',
            '-',
            substr($locale, 0, strpos($locale, '.') ?: strlen($locale))
        );

        return Localization\format_locale($locale)
            ->getLanguage();
    }

}