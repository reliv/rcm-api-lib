<?php


namespace Reliv\RcmApiLib\Resource\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\ErrorMiddlewareInterface;

class ErrorHandler implements ErrorMiddlewareInterface
{
    protected $displayErrors = false;

    public function __construct()
    {
        $this->displayErrors = in_array(ini_get('display_errors'), ['1', 'On', 'true']);
    }

    /**
     * Process an incoming error, along with associated request and response.
     *
     * Accepts an error, a server-side request, and a response instance, and
     * does something with them; if further processing can be done, it can
     * delegate to `$out`.
     *
     * @see MiddlewareInterface
     * @param mixed $error
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke($error, Request $request, Response $response, callable $out = null)
    {
        if ($this->displayErrors) {
            $body = $response->getBody();
            $body->write($error);

            return $response->withBody($body)->withHeader('Content-Type', 'application/json');
        }

        return $out($request, $response);
    }
}
