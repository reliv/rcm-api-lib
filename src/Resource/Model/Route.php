<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Interface Route
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface Route
{
    /**
     * getRouteService
     *
     * @return string|null
     */
    public function getRouteService();

    /**
     * getRouteOptions
     *
     * @return Options
     */
    public function getRouteOptions();
}
