<?php

namespace Localization\Detectors;

use Localization\Detectable;

class Request implements Detectable {

    public function detect($request) {
        if( $locale = $request->segment(1) )
            return $locale;
    }

}