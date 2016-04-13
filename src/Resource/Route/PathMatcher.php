<?php

namespace Reliv\RcmApiLib\Resource\Route;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * interface PathMatcher
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface RouteMatcher
{
    /**
     * matches
     *
     * @param Request $request
     * @param array   $options
     *
     * @return bool
     */
    public function matches(Request $request, array $options);
    
    public function parse();
}
