<?php

namespace Reliv\RcmApiLib\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractSingleAssetController
{
    /**
     * Returns the config;
     *
     * Return format:
     * [
     *     'path' => __DIR__ . '/../../public/rcm-api-lib/dist/rcm-api-lib.js',
     *     'headers' => [
     *         'content-type', 'application/javascript'
     *     ]
     * ];
     *
     * @return array
     */
    protected abstract function getConfig();

    /**
     * __invoke
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $config = $this->getConfig();

        $body = $response->getBody();

        $body->write(file_get_contents($config['path']));

        foreach ($config['headers'] as $headerKey => $value) {
            $response = $response->withHeader($headerKey, $value);
        }

        return $response->withBody($body);
    }
}
