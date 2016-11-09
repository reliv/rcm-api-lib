<?php

namespace Reliv\RcmApiLib\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RcmApiLibJsController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class RcmApiLibJsController
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
        $js = file_get_contents(__DIR__ . '/../../public/rcm-api-lib/dist/rcm-api-lib.js');

        $body = $response->getBody();

        $body->write($js);

        $response = $response->withHeader('content-type', 'application/javascript');

        return $response->withBody($body);
    }
}
