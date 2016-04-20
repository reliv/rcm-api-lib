<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Model\RouteModel;

/**
 * interface RouteModelBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface RouteModelBuilder
{
    /**
     * build
     *
     * @return RouteModel
     */
    public function build();
}
