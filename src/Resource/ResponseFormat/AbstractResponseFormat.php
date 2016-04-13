<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

/**
 * Class AbstractResponseFormat
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
abstract class AbstractResponseFormat implements ResponseFormat
{
    /**
     * getOptionValue
     *
     * @param array  $options
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed|null
     */
    protected function getOptionValue(array $options, $key, $default = null)
    {
        if (array_key_exists($key, $options)) {
            return $options[$key];
        }

        return $default;
    }
}
