<?php

namespace Reliv\RcmApiLib\ResourceMiddleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class MainMiddleware
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class MainMiddleware implements MiddlewareInterface
{
    /**
     *
     * @param Request       $request
     * @param Response      $response
     * @param null|callable $out
     *
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        // route match or $out

        // pre - collection of middleware

        // pick method

        // run method pre

        // run method(Request $request, Response $response);

        return $response;
    }
}
