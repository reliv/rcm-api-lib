<?php
namespace Reliv\RcmApiLib\Resource\Route;

use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class Router
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface Route
{
    /**
     * REQUEST_ATTRIBUTE_ROUTE_PARAMS
     */
    const REQUEST_ATTRIBUTE_ROUTE_PARAMS = 'api-lib-resource-route-params';

    /**
     * match
     *
     * - If it matches, will set route properties
     * - If not matches, will return false
     *
     * @param Request $request
     * @param Options $options
     *
     * @return bool
     */
    public function match(Request $request, Options $options);
}
