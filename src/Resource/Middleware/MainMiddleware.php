<?php

namespace Reliv\RcmApiLib\Resource\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Builder\ResourceModelBuilder;
use Reliv\RcmApiLib\Resource\Builder\RouteModelBuilder;
use Reliv\RcmApiLib\Resource\Controller\ResourceController;
use Reliv\RcmApiLib\Resource\Exception\RouteException;
use Reliv\RcmApiLib\Resource\Model\ControllerModel;
use Reliv\RcmApiLib\Resource\Model\MethodModel;
use Reliv\RcmApiLib\Resource\Model\PreServiceModel;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Reliv\RcmApiLib\Resource\Model\RouteModel;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
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
     * @var ResourceModelBuilder
     */
    protected $resourceModelBuilder;

    /**
     * @var RouteModelBuilder
     */
    protected $routeModelBuilder;

    /**
     * MainMiddleware constructor.
     *
     * @param RouteModelBuilder    $routeModelBuilder
     * @param ResourceModelBuilder $resourceModelBuilder
     */
    public function __construct(
        RouteModelBuilder $routeModelBuilder,
        ResourceModelBuilder $resourceModelBuilder
    ) {
        $this->resourceModelBuilder = $resourceModelBuilder;
        $this->routeModelBuilder = $routeModelBuilder;
    }

    /**
     * getRouteModel
     *
     * @return RouteModel
     */
    public function getRouteModel()
    {
        return $this->routeModelBuilder->build();
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
        $routeModel = $this->getRouteModel();
        $routeOptions = $routeModel->getOptions();
        $routeOptions->set('httpVerb', $request->getMethod());

        /** @var Request $request */
        $request = $request->withAttribute(RouteModel::REQUEST_ATTRIBUTE_MODEL_ROUTE, $routeModel);

        $route = $routeModel->getService();

        $match = $route->match($request, $routeOptions);

        if (!$match) {
            return $out($request, $response);
        }

        $resourceKey = $routeModel->getRouteParam('resourceController');

        // @todo $params null check
        if (empty($resourceKey)) {
            throw new RouteException("'resourceController' param not found");
        }

        /** @var ResourceModel $resourceModel */
        $resourceModel = $this->getResourceModel($resourceKey);
        
        var_dump($resourceModel);die;

        /** @var Request $request */
        $request = $request->withAttribute(ResourceModel::REQUEST_ATTRIBUTE_MODEL_RESOURCE, $resourceModel);

        $uri = $request->getUri();
        $originalPath = $uri->getPath();

        $uri->withPath($routeModel->getRouteParam('resourceMethod'));

        /** @var MethodModel $methodModel */
        $methodModel = null;

        $availableMethods = $resourceModel->getMethodModels();

        /** @var MethodModel $availableMethod */
        foreach ($availableMethods as $availableMethod) {
            
            $match = $route->match($request);
            if ($match) {
                $methodModel = $availableMethod;
                break;
            }
        }

        $uri->withPath($originalPath);

        if (empty($methodModel)) {
            $response->withStatus($resourceModel->getMethodMissingStatus());

            return $response;
        }
        
        /** @var ControllerModel $controllerModel */
        $controllerModel = $resourceModel->getControllerModel();

        $controllerService = $controllerModel->getService();
        $controllerOptions = $controllerModel->getOptions();

        $middlewarePipe = new MiddlewarePipe();

        /** @var Request $request */
        $request = $request->withAttribute(
            MethodModel::REQUEST_ATTRIBUTE_MODEL_METHOD,
            $methodModel
        );

        /** @var PreServiceModel $resourcePreServiceModel */
        $resourcePreServiceModel = $resourceModel->getPreServiceModel();
        $resourcePreServiceServices = $resourcePreServiceModel->getServices();

        // resource controller pre
        foreach ($resourcePreServiceServices as $serviceAlias => $service) {
            $resourcePreServiceOptions = $resourcePreServiceModel->getOptions($serviceAlias);
            $middlewareOptions = new OptionsMiddleware($resourcePreServiceOptions);
            $middlewarePipe->pipe('/', $middlewareOptions);
            $middlewarePipe->pipe('/', $service);
        }

        /** @var PreServiceModel $resourceMethodPreServiceModel */
        $methodPreServiceModel = $methodModel->getPreServiceModel();
        $methodPreServiceServices = $methodPreServiceModel->getServices();

        // resource method pre
        foreach ($methodPreServiceServices as $serviceAlias => $service) {
            $methodPreServiceOptions = $methodPreServiceModel->getOptions($serviceAlias);
            $middlewareOptions = new OptionsMiddleware($methodPreServiceOptions);
            $middlewarePipe->pipe('/', $middlewareOptions);
            $middlewarePipe->pipe('/', $service);
        }

        // run method(Request $request, Response $response);
        $method = $methodModel->getName();
        $middlewareOptions = new OptionsMiddleware($controllerOptions);
        $middlewarePipe->pipe('/', $middlewareOptions);
        $middlewarePipe->pipe('/', $controllerService->$method);

        return $middlewarePipe($request, $response, $out);
    }
}
