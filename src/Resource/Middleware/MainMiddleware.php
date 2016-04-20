<?php

namespace Reliv\RcmApiLib\Resource\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Builder\ResourceModelBuilder;
use Reliv\RcmApiLib\Resource\Builder\ResponseFormatModelBuilder;
use Reliv\RcmApiLib\Resource\Builder\RouteModelBuilder;
use Reliv\RcmApiLib\Resource\Controller\ResourceController;
use Reliv\RcmApiLib\Resource\Exception\RouteException;
use Reliv\RcmApiLib\Resource\Model\ControllerModel;
use Reliv\RcmApiLib\Resource\Model\MethodModel;
use Reliv\RcmApiLib\Resource\Model\PreServiceModel;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Reliv\RcmApiLib\Resource\Model\ResponseFormatModel;
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
     * Request Attribute Id
     */
    const REQUEST_ATTRIBUTE_RESOURCE_KEY = 'api-lib-resource-key';

    /**
     * Request Attribute Id
     */
    const REQUEST_ATTRIBUTE_RESOURCE_METHOD_KEY = 'api-lib-resource-method-key';

    /**
     * @var ResourceModelBuilder
     */
    protected $resourceModelBuilder;

    /**
     * @var RouteModelBuilder
     */
    protected $routeModelBuilder;

    /**
     * @var ResponseFormatModelBuilder
     */
    protected $responseFormatModelBuilder;

    /**
     * MainMiddleware constructor.
     *
     * @param RouteModelBuilder          $routeModelBuilder
     * @param ResourceModelBuilder       $resourceModelBuilder
     * @param ResponseFormatModelBuilder $responseFormatModelBuilder
     */
    public function __construct(
        RouteModelBuilder $routeModelBuilder,
        ResourceModelBuilder $resourceModelBuilder,
        ResponseFormatModelBuilder $responseFormatModelBuilder
    ) {
        $this->resourceModelBuilder = $resourceModelBuilder;
        $this->routeModelBuilder = $routeModelBuilder;
        $this->responseFormatModelBuilder = $responseFormatModelBuilder;
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
     * getResponseFormatModel
     *
     * @param $resourceKey
     *
     * @return ResponseFormatModel
     */
    public function getResponseFormatModel($resourceKey)
    {
        return $this->responseFormatModelBuilder->build($resourceKey);
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
        try {
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
            $request = $request->withAttribute(self::REQUEST_ATTRIBUTE_RESOURCE_KEY, $resourceKey);

            // $resourceKey required
            if (empty($resourceKey)) {
                throw new RouteException("'resourceController' param not found");
            }

            /** @var ResourceModel $resourceModel */
            $resourceModel = $this->getResourceModel($resourceKey);
            $request = $request->withAttribute(
                ResourceModel::REQUEST_ATTRIBUTE_MODEL_RESOURCE,
                $resourceModel
            );

            /** @var ResponseFormatModel $responseFormatModel */
            $responseFormatModel = $this->getResponseFormatModel($resourceKey);
            $request = $request->withAttribute(
                ResponseFormatModel::REQUEST_ATTRIBUTE_MODEL_RESOURCE_FORMAT,
                $responseFormatModel
            );

            $originalUri = $request->getUri();
            $uri = $originalUri->withPath(
                $routeModel->getRouteParam('resourceMethod')
            );
            $tempRequest = $request->withUri($uri);

            /** @var MethodModel $methodModel */
            $methodModel = null;

            $availableMethods = $resourceModel->getMethodModels();

            /** @var MethodModel $availableMethod */
            foreach ($availableMethods as $availableMethod) {

                $routeOptions = new GenericOptions(
                    [
                        'path' => $availableMethod->getPath(),
                        'httpVerb' => $availableMethod->getHttpVerb(),
                    ]
                );

                $match = $route->match($tempRequest, $routeOptions);
                if ($match) {
                    $methodModel = $availableMethod;
                    break;
                }
            }

            if (empty($methodModel)) {
                return $response->withStatus($resourceModel->getMethodMissingStatus());
            }

            $request = $request->withAttribute(
                self::REQUEST_ATTRIBUTE_RESOURCE_METHOD_KEY,
                $methodModel->getName()
            );

 //var_dump($request->getAttributes()); die;

            /** @var Request $request */
            $request = $request->withAttribute(
                MethodModel::REQUEST_ATTRIBUTE_MODEL_METHOD,
                $methodModel
            );


            $middlewarePipe = new MiddlewarePipe();

            /** @var PreServiceModel $resourcePreServiceModel */
            $resourcePreServiceModel = $resourceModel->getPreServiceModel();
            $resourcePreServiceServices = $resourcePreServiceModel->getServices();

// var_dump($resourcePreServiceServices); die;

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
//var_dump($methodPreServiceServices); die;
            // resource method pre
            foreach ($methodPreServiceServices as $serviceAlias => $service) {
                $methodPreServiceOptions = $methodPreServiceModel->getOptions($serviceAlias);
                $middlewareOptions = new OptionsMiddleware($methodPreServiceOptions);
                $middlewarePipe->pipe('/', $middlewareOptions);
                $middlewarePipe->pipe('/', $service);
            }

            /** @var ControllerModel $controllerModel */
            $controllerModel = $resourceModel->getControllerModel();
            $controllerService = $controllerModel->getService();
            $controllerOptions = $controllerModel->getOptions();

//var_dump($controllerOptions); die;

            // run method(Request $request, Response $response);
            $method = $methodModel->getName();
//var_dump($method); die;
            $request = $request->withAttribute(OptionsMiddleware::REQUEST_ATTRIBUTE_OPTIONS, $controllerOptions);
$cresp = $controllerService->$method($request, $response);
var_dump($cresp->getBody()->getContents()); die;
            $middlewareOptions = new OptionsMiddleware($controllerOptions);
            $middlewarePipe->pipe('/', $middlewareOptions);
            $middlewarePipe->pipe('/', [$controllerService, $method]);
            /** @var Response $response */
            $response = $middlewarePipe($request, $response, $out);
//var_dump($response->getBody()->getContents()); die;
            
        } catch (\Exception $e) {
            var_dump($e);
            exit(1);
        }
        return $response;
    }
}
