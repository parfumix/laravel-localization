<?php

namespace Localization;

interface Detectable {

    /**
     * Detect locale .
     *
     * @param $request
     * @return mixed
     */
    public function detect($request);
}