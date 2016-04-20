<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Model\Resource as ResourceModel;
use Reliv\RcmApiLib\Resource\Options\Options;
use Reliv\RcmApiLib\Resource\Route\Route;

/**
 * Class AbstractResourceController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
abstract class AbstractResourceController implements ResourceController
{
    /**
     * getEntityName
     *
     * @param Request $request
     *
     * @return Options
     */
    protected function getControllerOptions(Request $request)
    {
        /** @var Options $options */
        $options = $request->getAttribute(ResourceController::REQUEST_ATTRIBUTE_CONTROLLER_OPTIONS);

        return $options;
    }

    /**
     * getControllerOption
     *
     * @param Request $request
     * @param string  $key
     * @param null    $default
     *
     * @return Options
     */
    protected function getControllerOption(Request $request, $key, $default = null)
    {
        /** @var Options $options */
        $options = $this->getControllerOptions($request);

        return $options->get($key, $default);
    }

    /**
     * getUrlParam
     *
     * @param Request $request
     * @param string  $key
     * @param null    $default
     *
     * @return null
     */
    protected function getRouteParam(Request $request, $key, $default = null)
    {
        $resourceUrlParams = $request->getAttribute(Route::REQUEST_ATTRIBUTE_ROUTE_PARAMS, []);

        if (array_key_exists($key, $resourceUrlParams)) {
            return $resourceUrlParams[$key];
        }

        return $default;
    }

    /**
     * buildApiResponse
     *
     * @param Request  $request
     * @param Response $response
     * @param Options  $options
     * @param mixed     $dataModel
     *
     * @return Response
     */
    protected function buildApiResponse(
        Request $request,
        Response $response,
        Options $options,
        $dataModel = null
    ) {
        /** @var ResourceModel $resourceModel */
        $resourceModel = $request->getAttribute(ResourceModel::REQUEST_ATTRIBUTE_RESOURCE_MODEL);

        $responseFormat = $resourceModel->getResponseFormatService();
        
        
        return $response;
    }

}
