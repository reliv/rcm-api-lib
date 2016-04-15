<?php
namespace Reliv\RcmApiLib\Resource\Route;

use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Options\RuntimeOptions;

/**
 * Class Router
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface Route extends RuntimeOptions
{
    /**
     * PARAMS_NAME
     */
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
