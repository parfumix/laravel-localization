<?php

namespace Localization\Detectors;

use Localization\Detectable;
use Localization;

class Browser implements Detectable {

    public function detect($request) {
        $acceptLanguages = $request->header('accept-language');

        if( isset($acceptLanguages) && strlen($acceptLanguages) > 1 )  {
            $acceptLanguages = explode(",", $acceptLanguages);

            $lang = [];
            foreach ($acceptLanguages as $language) {
                if( preg_match( "/(.*);q=([0-1]{0,1}.\\d{0,4})/i", $language, $matches ) )
                    $lang[$matches[1]] = (float)$matches[2];
                else
                    $lang[$language] = 1.0;
            }

            $qval = 0.0;
            foreach ($lang as $key => $value) {
                if ($value > $qval) {
                    $qval = (float)$value;
                    $deflang = $key;
                }
            }
        }

        return Localization\format_locale($deflang)
            ->getLanguage();
    }

}