<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Http\BasicApiResponse;
use Reliv\RcmApiLib\Resource\Middleware\OptionsMiddleware;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Reliv\RcmApiLib\Resource\Model\ResponseFormatModel;
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
     * getResponseFormat
     *
     * @return ResponseFormatModel
     */
    protected function getResponseFormatModel(Request $request)
    {
        return $request->getAttribute(ResponseFormatModel::REQUEST_ATTRIBUTE_MODEL_RESOURCE_FORMAT);
    }

    /**
     * getResponseFormat
     *
     * @return ResponseFormat
     */
    protected function getResponseFormat(Request $request)
    {
        /** @var ResponseFormatModel $responseFormatModel */
        $responseFormatModel = $this->getResponseFormatModel($request);
        return $responseFormatModel->getService();
    }

    /**
     * format
     *
     * @param Request  $request
     * @param Response $response
     * @param null     $dataModel
     *
     * @return Response
     */
    protected function formatResponse(
        Request $request,
        Response $response,
        $dataModel = null
    ) {
        $responseFormat = $this->getResponseFormat($request);
        return $responseFormat->build($request, $response, $dataModel);
    }

    /**
     * buildApiResponse
     *
     * @param Request  $request
     * @param Response $response
     * @param mixed     $dataModel
     *
     * @return Response
     */
    protected function buildApiResponse(
        Request $request,
        Response $response,
        $dataModel = null,
        $messages = []
    ) {
        // hydrate messages
        // build new BasicApiResponse
        $apiResponse = new BasicApiResponse(
            $dataModel,
            $messages
        );
        return $this->getResponseFormat($request)->build($request, $response, $dataModel);
    }

}
