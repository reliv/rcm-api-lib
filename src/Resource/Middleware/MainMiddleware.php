<?php

namespace Reliv\RcmApiLib\Resource\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Builder\ResourceModelBuilder;
use Reliv\RcmApiLib\Resource\Controller\ResourceController;
use Reliv\RcmApiLib\Resource\Exception\RouteException;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
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
     * @var ResourceModelBuilder
     */
    protected $resourceModelBuilder;

    /**
     * MainMiddleware constructor.
     *
     * @param array                   $config
     * @param ServiceLocatorInterface $serviceManager
     * @param ResourceModelBuilder    $resourceModelBuilder
     */
    public function __construct(
        $config,
        ServiceLocatorInterface $serviceManager,
        ResourceModelBuilder $resourceModelBuilder
    ) {
        $this->config = $config['Reliv\\RcmApiLib'];
        $this->serviceManager = $serviceManager;
        $this->resourceModelBuilder = $resourceModelBuilder;
    }

    /**
     * getResourceModel
     *
     * @param $resourceKey
     *
     * @return ResourceModel
     */
    public function getResourceModel($resourceKey)
    {
        return $this->resourceModelBuilder->build($resourceKey);
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

        $resourceModel->

        $uri = $request->getUri();
        $originalPath = $uri->getPath();

        $uri->withPath($params['resourceMethod']);

        $methodModel = null;

        $availableMethods = $resourceModel->getMethods();

        /** @var MethodModel $availableMethod */
        foreach ($availableMethods as $availableMethod) {
            $match = $route->match($request, $availableMethod->getPath());
            if ($match) {
                $methodModel = $availableMethod;
                break;
            }
        }

        $uri->withPath($originalPath);

        if (empty($methodModel)) {
            $response->withStatus($resourceModel->getMissingMethodStatus());

            return $response;
        }

        $controllerServiceName = $resourceModel->getControllerService();

        $middlewarePipe = new MiddlewarePipe();

        $request->withAttribute(
            ResourceController::REQUEST_ATTRIBUTE_CONTROLLER_OPTIONS, 
            $resourceModel->getControllerOptions()
        );

        $request->withAttribute(
            ResourceModel::REQUEST_ATTRIBUTE_RESOURCE_MODEL,
            $resourceModel
        );

        $request->withAttribute(
            MethodModel::REQUEST_ATTRIBUTE_METHOD_MODEL,
            $methodModel
        );

        $resourcePreServices = $resourceModel->getPreServices();

        // resource controller pre
        foreach ($resourcePreServices as $serviceName) {

            $service = $this->serviceManager->get($serviceName);

            $middlewarePipe->pipe('/', $service);
        }

        $resourceMethodPreServices = $methodModel->getPreServices();
        
        // resource method pre
        foreach ($resourceMethodPreServices as $serviceName => $options) {

            $service = $this->serviceManager->get($serviceName);

            $middlewarePipe->pipe('/', $service);
        }

        $resourceController = $this->serviceManager->get($controllerServiceName);

        // run method(Request $request, Response $response);
        $method = $methodModel->getName();
        $middlewarePipe->pipe('/', $resourceController->$method);

        return $middlewarePipe($request, $response, $out);
    }
}
