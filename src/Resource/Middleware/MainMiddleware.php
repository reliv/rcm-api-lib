<?php

namespace Reliv\RcmApiLib\Resource\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Exception\RouteException;
use Reliv\RcmApiLib\Resource\Model\ControllerModel;
use Reliv\RcmApiLib\Resource\Model\RouteModel;
use Reliv\RcmApiLib\Resource\Model\ServiceModelCollection;
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
class MainMiddleware extends AbstractModelMiddleware implements Middleware
{
    /**
     * pipe
     *
     * @param MiddlewarePipe $middlewarePipe
     * @param ServiceModelCollection $model
     *
     * @return void
     */
    public function pipe(
        MiddlewarePipe $middlewarePipe,
        ServiceModelCollection $model
    ) {
        $services = $model->getServices();

        // resource controller pre
        foreach ($services as $serviceAlias => $service) {
            $options = $model->getOptions(
                $serviceAlias
            );
            $middlewareOptions = new OptionsMiddleware($options);
            $middlewarePipe->pipe($middlewareOptions);
            $middlewarePipe->pipe($service);
        }
    }

    /**
     * __invoke
     *
     * @param Request $request
     * @param Response $response
     * @param callable|null $out
     *
     * @return mixed
     * @throws RouteException
     */
    public function __invoke(
        Request $request,
        Response $response,
        callable $out = null
    ) {
        $routeModel = $this->getRouteModel($request);

        /// /// /// /// /// /// /// ///

        // ROUTE
        $routePipe = new MiddlewarePipe();

        $this->pipe($routePipe, $routeModel);
        $routePipe->pipe([$this, 'postRoute']);
        $routePipe->pipe(new ErrorHandler());

        return $routePipe(
            $request,
            $response,
            $out
        );
    }

    /**
     * postRoute
     *
     * @param Request $request
     * @param Response $response
     * @param callable|null $out
     *
     * @return mixed
     */
    public function postRoute(
        Request $request,
        Response $response,
        callable $out = null
    ) {
        if (empty($this->getResourceKey($request))) {
            return $out($request, $response);
        }

        if (empty($this->getMethodKey($request))) {
            return $out($request, $response);
        }

        $resourceModel = $this->getResourceModel($request);
        $methodModel = $this->getMethodModel($request);

        $middlewarePipe = new MiddlewarePipe();

        /** @var ServiceModelCollection $resourcePreServiceModel */
        $resourcePreServiceModel = $resourceModel->getPreServiceModel();
        $this->pipe($middlewarePipe, $resourcePreServiceModel);

        /** @var ServiceModelCollection $resourceMethodPreServiceModel */
        $methodPreServiceModel = $methodModel->getPreServiceModel();
        $this->pipe($middlewarePipe, $methodPreServiceModel);

        /** @var ControllerModel $controllerModel */
        $controllerModel = $resourceModel->getControllerModel();
        $controllerService = $controllerModel->getService();
        $controllerOptions = $controllerModel->getOptions();

        // run method(Request $request, Response $response);
        $method = $methodModel->getName();

        $request = $request->withAttribute(
            OptionsMiddleware::REQUEST_ATTRIBUTE_OPTIONS,
            $controllerOptions
        );

        $middlewareOptions = new OptionsMiddleware($controllerOptions);
        $middlewarePipe->pipe($middlewareOptions);
        $middlewarePipe->pipe([$controllerService, $method]);

        /** @var ServiceModelCollection $resourceMethodPostServiceModel */
        $methodPostServiceModel = $methodModel->getPostServiceModel();
        $this->pipe($middlewarePipe, $methodPostServiceModel);

        /** @var ServiceModelCollection $resourcePostServiceModel */
        $resourcePostServiceModel = $resourceModel->getPostServiceModel();
        $this->pipe($middlewarePipe, $resourcePostServiceModel);

        /** @var ServiceModelCollection $resourcePostServiceModel */
        $resourceFinalServiceModel = $resourceModel->getFinalServiceModel();
        $this->pipe($middlewarePipe, $resourceFinalServiceModel);

        return $middlewarePipe($request,$response, $out);
    }
}
