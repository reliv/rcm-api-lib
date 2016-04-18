<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Route\Route;

/**
 * Class AbstractResourceController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
abstract class AbstractResourceController implements ResourceController
{
    /**
     * getUrlParam
     *
     * @param Request $request
     * @param string  $key
     * @param null    $default
     *
     * @return null
     */
    protected function getRouteParam(Request $request, $key, $default = null)
    {
        $resourceUrlParams = $request->getAttribute(Route::REQUEST_ATTRIBUTE_ROUTE_PARAMS, []);

        if (array_key_exists($key, $resourceUrlParams)) {
            return $resourceUrlParams[$key];
        }

        return $default;
    }

}
