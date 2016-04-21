<?php


namespace Reliv\RcmApiLib\Resource\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\ErrorMiddlewareInterface;

class ErrorHandler implements ErrorMiddlewareInterface
{
    protected $displayErrors = false;

    protected $letExceptionsBubbleToTop = true;

    /**
     * @param bool $letExceptionsBubbleToTop If this is true, exceptions will fly
     * and PHP will halt. We suggest setting this to true unless you are running PHP
     * as a long running service similar to a node app.
     */
    public function __construct($letExceptionsBubbleToTop = true)
    {
        $this->letExceptionsBubbleToTop = $letExceptionsBubbleToTop;
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
        if ($this->letExceptionsBubbleToTop) {
            //Insure the error shows up in the php error.log
            trigger_error($error, E_USER_ERROR);
            //Execution does NOT proceed past this line.
        }

        //Show error in browser if display_errors is on in php.ini
        if ($this->displayErrors) {
            $body = $response->getBody();
            $body->write($error);

            return $response->withBody($body);
        }

        $body = $response->getBody();
        $body->write('500 Internal Server Error');

        return $response->withBody($body);
    }
}
