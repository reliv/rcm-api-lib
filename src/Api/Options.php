<?php

namespace Reliv\RcmApiLib\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class Options
{
    /**
     * @param array  $options
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    public static function getOption(array $options, $key, $default = null)
    {
        if (array_key_exists($key, $options)) {
            return $options[$key];
        }

        return $default;
    }
}
