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
interface Route
{
    const PARAMS_NAME = 'resource-route-params';
    
    /**
     * match
     *
     * - If it matches, will set route properties
     * - If not matches, will retur false
     *
     * @param Request $request
     * @param array   $options
     *
     * @return bool
     */
    public function match(Request $request, array $options);
}
