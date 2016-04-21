<?php


namespace Reliv\RcmApiLib\Resource\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;

class JsonBodyParser implements MiddlewareInterface
{
    /**
     * If the request is of type application/json, this middleware
     * decodes the json in the body and puts it in the "body" attribute
     * in the request.
     *
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $contentTypeParts = explode(';', $request->getHeader('Content-Type'));

        if (in_array('application/json', $contentTypeParts)) {
            $body = json_decode($request->getBody()->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $body = $response->getBody();
                $body->write(
                    'MIME type was "application/json" but invalid JSON in request body.'
                );

                return $response->withStatus(400)->withBody($body);
            }

            $request = $request->withAttribute('body', $body);
        }

        return $out($request, $response);
    }
}
