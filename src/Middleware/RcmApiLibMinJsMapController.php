<?php

namespace Reliv\RcmApiLib\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RcmApiLibMinJsMapController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class RcmApiLibMinJsMapController
{
    /**
     * __invoke
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param callable|null     $next
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $js = file_get_contents(__DIR__ . '/../../public/rcm-api-lib/dist/rcm-api-lib.min.js.map');

        $body = $response->getBody();

        $body->write($js);

        $response = $response->withHeader('content-type', ['text/plain','charset=UTF-8']);

        return $response->withBody($body);
    }
}
