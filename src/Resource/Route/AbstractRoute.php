<?php
namespace Reliv\RcmApiLib\Resource\Route;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class Router
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
abstract class AbstractRoute implements Route
{
    /**
     * getOption
     *
     * @param array  $options
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    protected function getOption(array $options, $key, $default = null)
    {
        if (array_key_exists($key, $options)) {
            return $options[$key];
        }

        return $default;
    }
}
