<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Middleware\OptionsMiddleware;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Reliv\RcmApiLib\Resource\Model\RouteModel;
use Reliv\RcmApiLib\Resource\Options\Options;
use Reliv\RcmApiLib\Resource\ResponseFormat\ResponseFormat;
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
    protected function getOptions(Request $request)
    {
        /** @var Options $options */
        $options = $request->getAttribute(OptionsMiddleware::REQUEST_ATTRIBUTE_OPTIONS);

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
    protected function getOption(Request $request, $key, $default = null)
    {
        /** @var Options $options */
        $options = $this->getOptions($request);

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
        /** @var RouteModel $routeModel */
        $routeModel = $request->getAttribute(RouteModel::REQUEST_ATTRIBUTE_MODEL_ROUTE, []);

        return $routeModel->getRouteParam($key, $default);
    }
    /**
     * buildResponse
     *
     * @param Request  $request
     * @param Response $response
     * @param Options  $options
     * @param mixed     $dataModel
     *
     * @return Response
     */
    protected function buildResponse(
        Request $request,
        Response $response,
        Options $options,
        $dataModel = null
    ) {
        /** @var ResourceModel $resourceModel */
        $resourceModel = $request->getAttribute(ResourceModel::REQUEST_ATTRIBUTE_MODEL_RESOURCE);

        $responseFormatModel = $resourceModel->getResponseFormatModel();

        /** @var ResponseFormat $responseFormat */
        $responseFormat = $responseFormatModel->getService();
        $responseOptions = $responseFormatModel->getOptions();

        return $responseFormat->build($request, $response, $responseOptions, $dataModel);
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
        $resourceModel = $request->getAttribute(ResourceModel::REQUEST_ATTRIBUTE_MODEL_RESOURCE);

        $responseFormatModel = $resourceModel->getResponseFormatModel();

        /** @var ResponseFormat $responseFormat */
        $responseFormat = $responseFormatModel->getService();
        $responseOptions = $responseFormatModel->getOptions();
        
        return $responseFormat->build($request, $response, $responseOptions, $dataModel);
    }

}
