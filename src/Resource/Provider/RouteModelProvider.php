<?php

namespace Reliv\RcmApiLib\Resource\Provider;

use Reliv\RcmApiLib\Resource\Model\RouteModel;

/**
 * interface RouteModelProvider
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface RouteModelProvider
{
    /**
     * get
     *
     * @return RouteModel
     */
    public function get();
}
