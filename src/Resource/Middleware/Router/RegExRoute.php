<?php

namespace Reliv\RcmApiLib\Resource\Middleware\Router;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Exception\RouteException;
use Reliv\RcmApiLib\Resource\Middleware\AbstractModelMiddleware;
use Reliv\RcmApiLib\Resource\Middleware\Middleware;
use Reliv\RcmApiLib\Resource\Model\MethodModel;
use Reliv\RcmApiLib\Resource\Model\RouteModel;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;

/**
 * Class RegExRoute
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Resource\Middleware
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RegExRoute extends AbstractModelMiddleware implements Middleware
{
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
    public function __invoke(
        Request $request,
        Response $response,
        callable $out = null
    ) {
        $routeModel = $this->getRouteModel($request);
        $routeOptions = $this->getOptions($request);
        $routeOptions->set('httpVerb', $request->getMethod());

        /** @var Request $request */
        $request = $request->withAttribute(
            RouteModel::REQUEST_ATTRIBUTE_MODEL_ROUTE,
            $routeModel
        );

        $route = new \Reliv\RcmApiLib\Resource\Route\RegexRoute();

        $match = $route->match($request, $routeOptions);

        if (!$match) {
            return $out($request, $response);
        }

        $resourceKey = $routeModel->getRouteParam('resourceController');

        $request = $request->withAttribute(
            self::REQUEST_ATTRIBUTE_RESOURCE_KEY,
            $resourceKey
        );

        // $resourceKey required
        if (empty($resourceKey)) {
            throw new RouteException("'resourceController' param not found");
        }

        /** @var ResourceModel $resourceModel */
        $resourceModel = $this->getResourceModel($request);
        $routeModel = $this->getRouteModel($request);

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
            return $out($request, $response);
        }

        $request = $request->withAttribute(
            self::REQUEST_ATTRIBUTE_RESOURCE_METHOD_KEY,
            $methodModel->getName()
        );

        /** @var Response $response */
        return $out($request, $response);
    }
}
