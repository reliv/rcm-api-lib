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
 * Class CurlyBraceVarRouter This is a router that allows paths like /fun/{id}
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
class CurlyBraceVarRouter extends AbstractModelMiddleware implements Middleware
{
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

        //It is every router's job to add the RouteModel attribute to the request
        /** @var Request $request */
        $request = $request->withAttribute(
            RouteModel::REQUEST_ATTRIBUTE_MODEL_ROUTE,
            $routeModel
        );

        $uriParts = explode('/', $request->getUri()->getPath());

        //Cut off the first /
        array_shift($uriParts);

        if (count($uriParts) == 0 || empty($uriParts[0])) {
            //Route is not for us so leave
            return $out($request, $response);
        }

        $resourceKey = $uriParts[0];

        $request = $request->withAttribute(
            self::REQUEST_ATTRIBUTE_RESOURCE_KEY,
            $resourceKey
        );

        //Cut the resource key off the path. We don't need it anymore
        array_shift($uriParts);
        $uri = implode('/', $uriParts);

        /** @var ResourceModel $resourceModel */
        $resourceModel = $this->getResourceModel($request);

        /** @var MethodModel $methodModel */
        $methodModel = null;

        $availableMethods = $resourceModel->getMethodModels();

        /** @var MethodModel $availableMethod */
        foreach ($availableMethods as $availableMethod) {
            /** @var RouteModel $routeModel */
            $routeModel = $request->getAttribute(RouteModel::REQUEST_ATTRIBUTE_MODEL_ROUTE);

            $path = $availableMethod->getPath();
            $httpVerb = $availableMethod->getHttpVerb();

            if (empty($path)) {
                throw new RouteException('Path option required');
            }

            $regex = '/' . str_replace(['{', '}', '/'], ['(?<', '>[^/]+)', '\/'], $path) . '/';

            // $uri = '/api/resource/hi/there/oh/yeah';
            $routeMatched = (bool)preg_match($regex, $uri, $captures);
            $verbMatched = empty($httpVerb) || $request->getMethod() === $httpVerb;

            if (!$routeMatched || !$verbMatched) {
                //Route did not match, try next one.
                continue;
            }

            $methodModel = $availableMethod;

            foreach ($captures as $key => $val) {
                if (!is_numeric($key)) {
                    $routeModel->setRouteParam($key, $val);
                }
            }

            break;
        }

        if (empty($methodModel)) {
            //Route is not for us so leave
            return $out($request, $response);
        }

        $request = $request->withAttribute(
            self::REQUEST_ATTRIBUTE_RESOURCE_METHOD_KEY,
            $methodModel->getName()
        );

        return $out($request, $response);
    }
}
