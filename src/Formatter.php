<?php

namespace Localization;

class Formatter {

    /**
     * Type: langtag (the real one).
     *
     * @const int
     */
    const TYPE_LANGTAG       = 0;

    /**
     * Type: private use.
     *
     * @const int
     */
    const TYPE_PRIVATEUSE    = 1;

    /**
     * Type: grandfathered.
     *
     * @const int
     */
    const TYPE_GRANDFATHERED = 2;

    /**
     * Language.
     *
     * @var string
     */
    protected $_language = null;

    /**
     * Script.
     *
     * @var string
     */
    protected $_script = null;

    /**
     * Region.
     *
     * @var string
     */
    protected $_region = null;

    /**
     * Variants.
     *
     * @var array
     */
    protected $_variant = null;

    /**
     * Type
     *
     * @var null
     */
    protected $_type = null;

    /**
     * Extensions.
     *
     * @var array
     */
    protected $_extension      = null;

    /**
     * Private use.
     *
     * @var mixed
     */
    protected $_privateuse     = null;

    /**
     * Grandfathered.
     *
     * @var string
     */
    protected $_grandfathered  = null;


    /**
     * Parse a local.
     * Please, see RFC4646, 2.1 Syntax.
     *
     * @param   string $locale Locale.
     * @return  array
     */
    public function parse($locale) {
        // RFC4646
        $match = preg_match(
            '#^
             (
               (?<r_langtag>
                 (?<language>[a-z]{2,3})
                 (?<script>\-[a-z]{4})?
                 (?<region>\-(?:[a-z]{2}|[0-9]{4}))?
                 (?<variant>(?:\-(?:[a-z]{2}|[0-9]{3}))+)?
                 (?<extension>(?:\-(?:[a-wy-z]|\d)\-[a-z0-9]{2,8})+)?
                 (?<privateuse>\-x\-[a-z0-9]{1,8})?
               )
             | (?<r_privateuse>x\-[a-z0-9]{1,8})
             | (?<r_grandfathered>[a-z]{1,3}(\-[a-z0-9]{2,8}){1,2})
             )
             $#ix',
            $locale,
            $matches
        );

        if (0 === $match) {
            return false;
        }

        if (isset($matches['r_grandfathered'])) {
            return [
                'grandfathered' => $matches['r_grandfathered']
            ];
        }

        if (isset($matches['r_privateuse'])) {
            return [
                'privateuse' => substr($matches['r_privateuse'], 2)
            ];
        }

        $out = [
            'language' => $matches['language'],
            'script' => null,
            'region' => null,
            'variant' => [],
            'extension' => [],
            'privateuse' => null
        ];

        if (! empty($matches['script'])) {
            $out['script'] = substr($matches['script'], 1);
        }

        if (! empty($matches['region'])) {
            $out['region'] = substr($matches['region'], 1);
        }

        if (! empty($matches['variant'])) {
            $out['variant'] = explode('-', substr($matches['variant'], 1));
        }

        if (! empty($matches['extension'])) {
            $handle = preg_split(
                '/-(?=.-)/',
                $matches['extension'],
                -1,
                PREG_SPLIT_NO_EMPTY
            );

            foreach ($handle as $value) {
                list($extensionName, $extensionValue) = explode('-', $value);
                $out['extension'][$extensionName] = $extensionValue;
            }
        }

        if (! empty($matches['privateuse'])) {
            $out['privateuse'] = substr($matches['privateuse'], 3);
        }

        if (isset($out['grandfathered'])) {
            $this->_type          = static::TYPE_GRANDFATHERED;
            $this->_grandfathered = $out['grandfathered'];
        } elseif (isset($out['privateuse'])) {
            $this->_type       = static::TYPE_PRIVATEUSE;
            $this->_privateuse = $out['privateuse'];
        } else {
            $this->_type = static::TYPE_LANGTAG;
            list(
                $this->_language,
                $this->_script,
                $this->_region,
                $this->_variant,
                $this->_extension,
                $this->_privateuse
                ) = array_values($out);
        }

        return $this;
    }


    /**
     * Get type. Please, see static::TYPE_* constants.
     *
     * @return  int
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * Get language.
     *
     * @return  string
     */
    public function getLanguage() {
        return $this->_language;
    }

    /**
     * Get script.
     *
     * @return  string
     */
    public function getScript() {
        return $this->_script;
    }

    /**
     * Get region.
     *
     * @return  string
     */
    public function getRegion() {
        return $this->_region;
    }

    /**
     * Get all variants.
     *
     * @return  array
     */
    public function getVariants() {
        return $this->_variant;
    }

    /**
     * Get extensions.
     *
     * @return  array
     */
    public function getExtensions() {
        return $this->_extension;
    }

    /**
     * Get private use.
     *
     * @return  mixed
     */
    public function getPrivateUse() {
        return $this->_privateuse;
    }

    /**
     * Get grand-fathered value.
     *
     * @return  string
     */
    public function getGrandfathered() {
        return $this->_grandfathered;
    }
}

