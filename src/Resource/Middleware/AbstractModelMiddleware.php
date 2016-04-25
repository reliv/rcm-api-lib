<?php

namespace Reliv\RcmApiLib\Resource\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Provider\ResourceModelProvider;
use Reliv\RcmApiLib\Resource\Provider\RouteModelProvider;
use Reliv\RcmApiLib\Resource\Model\MethodModel;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Reliv\RcmApiLib\Resource\Model\RouteModel;

/**
 * Class AbstractModelMiddleware
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   MiddlewareInterface
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class AbstractModelMiddleware extends AbstractMiddleware
{
    /**
     * @var ResourceModelProvider
     */
    protected $resourceModelProvider;

    /**
     * @var RouteModelProvider
     */
    protected $routeModelProvider;

    /**
     * MainMiddleware constructor.
     *
     * @param RouteModelProvider    $routeModelProvider
     * @param ResourceModelProvider $resourceModelProvider
     */
    public function __construct(
        RouteModelProvider $routeModelProvider,
        ResourceModelProvider $resourceModelProvider
    )
    {
        $this->resourceModelProvider = $resourceModelProvider;
        $this->routeModelProvider = $routeModelProvider;
    }

    /**
     * getRouteModel
     *
     * @param Request $request
     * @param null    $default
     *
     * @return mixed|RouteModel
     */
    public function getRouteModel(
        Request $request,
        $default = null
    ) {
        $routeModel = $request->getAttribute(
            RouteModel::REQUEST_ATTRIBUTE_MODEL_ROUTE,
            $default
        );

        if ($routeModel instanceof RouteModel) {
            return $routeModel;
        }

        return $this->routeModelProvider->get();
    }

    /**
     * getResourceModel
     *
     * @param Request $request
     * @param string  $resourceKey
     *
     * @return ResourceModel
     */
    public function getResourceModel(
        Request $request,
        $resourceKey = null
    ) {
        $resourceKey = $this->getResourceKey($request, $resourceKey);

        return $this->resourceModelProvider->get($resourceKey);
    }

    /**
     * getMethodModel
     *
     * @param Request $request
     * @param null    $resourceKey
     * @param null    $methodKey
     *
     * @return null|MethodModel
     */
    public function getMethodModel(
        Request $request,
        $resourceKey = null,
        $methodKey = null
    ) {
        $resourceModel = $this->getResourceModel($request, $resourceKey);

        return $resourceModel->getMethodModel(
            $this->getMethodKey($request, $methodKey)
        );
    }
}
