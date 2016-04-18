<?php

namespace Reliv\RcmApiLib\Resource\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Builder\ResourceModelBuilder;
use Reliv\RcmApiLib\Resource\Controller\ResourceController;
use Reliv\RcmApiLib\Resource\Exception\RouteException;
use Reliv\RcmApiLib\Resource\Model\Method;
use Reliv\RcmApiLib\Resource\Model\Resource as ResourceModel;
use Reliv\RcmApiLib\Resource\Model\Route as RouteModel;
use Reliv\RcmApiLib\Resource\Options\Options;
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
     * @var Route
     */
    protected $route;

    /**
     * @var RouteModel
     */
    protected $routeModel;

    /**
     * @var ResourceModelBuilder
     */
    protected $resourceModelBuilder;

    /**
     * MainMiddleware constructor.
     *
     * @param array                   $config
     * @param ServiceLocatorInterface $serviceManager
     * @param RouteModel              $routeModel
     * @param ResourceModelBuilder    $resourceModelBuilder
     */
    public function __construct(
        $config,
        ServiceLocatorInterface $serviceManager,
        RouteModel $routeModel,
        ResourceModelBuilder $resourceModelBuilder
    ) {
        $this->config = $config['Reliv\\RcmApiLib'];
        $this->serviceManager = $serviceManager;
        $this->routeModel = $routeModel;
        $this->resourceModelBuilder = $resourceModelBuilder;
    }

    /**
     * getRoute
     *
     * @return Route
     */
    public function getRoute()
    {
        $service = $this->routeModel->getRouteService();

        return $this->serviceManager->get($service);
    }

    /**
     * getRouteOptions
     *
     * @return Options
     */
    public function getRouteOptions()
    {
        return $this->routeModel->getRouteOptions();
    }

    /**
     * getResourceModel
     *
     * @param string $resourceControllerKey
     *
     * @return ResourceModel
     */
    public function getResourceModel($resourceControllerKey)
    {
        return $this->resourceModelBuilder->build($resourceControllerKey);
    }

    /**
     * __invoke
     *
     * @param Request       $request
     * @param Response      $response
     * @param callable|null $out
     *
     * @return mixed
     * @throws RouteException
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $route = $this->getRoute();
        $routeOptions = $this->getRouteOptions();

        $match = $route->match($request, $routeOptions);

        if (!$match) {
            return $out();
        }

        $params = $request->getAttribute(Route::REQUEST_ATTRIBUTE_ROUTE_PARAMS);

        // @todo $params null check
        if ($params === null) {
            throw new RouteException('No route params found');
        }

        $resourceModel = $this->getResourceModel($params['resourceController']);

        $uri = $request->getUri();
        $originalPath = $uri->getPath();

        $uri->withPath($params['resourceMethod']);

        $method = null;

        $availableMethods = $resourceModel->getMethods();

        /** @var Method $availableMethod */
        foreach ($availableMethods as $availableMethod) {
            $match = $route->match($request, $availableMethod->get());
            if ($match) {
                $method = $availableMethod;
                break;
            }
        }

        $uri->withPath($originalPath);

        if (empty($method)) {
            $response->withStatus($resourceModel->getMissingMethodStatus());

            return $response;
        }

        // middleware
        // @todo check for pre first
        $controllerServiceOptions
            = $this->config['resource']['controllerRoute'][$params['resourceController']]['controller']['options'];
        $controllerServiceName
            = $this->config['resource']['controllerRoute'][$params['resourceController']]['controller']['service'];
        $controllerPre = $this->config['resource']['controllerRoute'][$params['resourceController']]['pre'];
        $methodPre
            = $this->config['resource']['controllerRoute'][$params['resourceController']]['methods'][$method]['pre'];
        
        
        $middlewarePipe = new MiddlewarePipe();

        $request->withAttribute(
            Middleware::REQUEST_ATTRIBUTE_MIDDLEWARE_OPTIONS,
            $resourceModel->getControllerOptions()
        );

        $request->withAttribute(
            ResourceController::REQUEST_ATTRIBUTE_CONTROLLER_OPTIONS, 
            $resourceModel->getControllerOptions()
        );

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
