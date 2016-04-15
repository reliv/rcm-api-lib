<?php

namespace Reliv\RcmApiLib\Resource\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Reliv\RcmApiLib\Resource\Controller\ResourceController;
use Reliv\RcmApiLib\Resource\Route\RegexRoute;
use Reliv\RcmApiLib\Resource\Route\Route;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stratigility\MiddlewarePipe;

/**
 * Class MainMiddleware
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class MainMiddleware implements Middleware
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;

    /**
     * MainMiddleware constructor.
     *
     * @param                         $config
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(
        $config,
        ServiceLocatorInterface $serviceManager
    ) {
        $this->config = $config['Reliv\\RcmApiLib'];
        $this->serviceManager = $serviceManager;
    }

    /**
     * getRoute
     *
     * @return Route
     */
    public function getRoute()
    {
        return new RegexRoute();
    }

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
        $route = $this->getRoute();

        $basePath = $this->getBasePath() ;
        $options = [
            'path' => $basePath,
        ];

        $match = $route->match($request, $options);

        if (!$match) {
            return $out();
        }

        $params = $request->getAttribute(Route::PARAMS_NAME);

        // @todo $params null check

        $methodConfig = $this->config['resource']['controllerRoute'][$params['resourceController']]['methods'];

        $allowedMethods = $this->config['resource']['controllerRoute'][$params['resourceController']]['allowedMethods'];

        $defaultMethodConfig = $this->config['resource']['defaultMethods'];

        $methodConfig = array_merge($defaultMethodConfig, $methodConfig);

        $uri = $request->getUri();
        $originalPath = $uri->getPath();

        $uri->withPath($params['resourceMethod']);

        $method = null;

        foreach ($allowedMethods as $allowedMethod) {
            if (array_key_exists($allowedMethod, $methodConfig)) {
                $match = $route->match($request, $options);
                if ($match) {
                    $method = $allowedMethod;
                    break;
                }
            }
        }

        $uri->withPath($originalPath);

        if (empty($method)) {
            $response->withStatus($this->config['resource']['config']['missingMethodStatus']);

            return $response;
        }

        // middleware
        // @todo check for pre first
        $controllerServiceOptions = $this->config['resource']['controllerRoute'][$params['resourceController']]['controller']['options'];
        $controllerServiceName = $this->config['resource']['controllerRoute'][$params['resourceController']]['controller']['service'];
        $controllerPre = $this->config['resource']['controllerRoute'][$params['resourceController']]['pre'];
        $methodPre = $this->config['resource']['controllerRoute'][$params['resourceController']]['methods'][$method]['pre'];
        $middlewarePipe = new MiddlewarePipe();

        $request->withAttribute(Middleware::OPTIONS_ATTRIBUTE, $controllerPre);
        $request->withAttribute(ResourceController::OPTIONS_ATTRIBUTE, $controllerServiceOptions);

        // controller pre
        foreach ($controllerPre as $serviceName => $options) {

            $service = $this->serviceManager->get($serviceName);

            $middlewarePipe->pipe('/', $service);
        }

        // method pre
        foreach ($methodPre as $serviceName => $options) {

            $service = $this->serviceManager->get($serviceName);

            $middlewarePipe->pipe('/', $service);
        }
        
        $resourceController = $service = $this->serviceManager->get($controllerServiceName);

        // run method(Request $request, Response $response);
        $middlewarePipe->pipe('/', $resourceController->$method);

        return $middlewarePipe($request, $response, $out);
    }
}
