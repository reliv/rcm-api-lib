<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Http\BasicApiResponse;
use Reliv\RcmApiLib\Resource\Exception\InvalidWhereException;
use Reliv\RcmApiLib\Resource\Exception\ResponseFormatException;
use Reliv\RcmApiLib\Resource\Http\BasicDataResponse;
use Reliv\RcmApiLib\Resource\Http\DataResponse;
use Reliv\RcmApiLib\Resource\Middleware\AbstractMiddleware;
use Reliv\RcmApiLib\Resource\Middleware\OptionsMiddleware;
use Reliv\RcmApiLib\Resource\Model\ResponseFormatModel;
use Reliv\RcmApiLib\Resource\Model\RouteModel;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Reliv\RcmApiLib\Resource\Options\Options;
use Reliv\RcmApiLib\Resource\ResponseFormat\ResponseFormat;

/**
 * Class AbstractResourceController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
abstract class AbstractResourceController extends AbstractMiddleware implements ResourceController
{
    /**
     * getUrlParam
     *
     * @param Request $request
     * @param string $key
     * @param null $default
     *
     * @return null
     */
    protected function getRouteParam(Request $request, $key, $default = null)
    {
        /** @var RouteModel $routeModel */
        $routeModel = $request->getAttribute(
            RouteModel::REQUEST_ATTRIBUTE_MODEL_ROUTE,
            []
        );

        return $routeModel->getRouteParam($key, $default);
    }

    public function getWhere(Request $request)
    {
        $allowDeepWheres = $this->getOption($request, 'allowDeepWheres', false);

        $params = $request->getQueryParams();
        if (!array_key_exists('where', $params)) {
            return [];
        }

        $where = json_decode($params['where'], true);

        if ($allowDeepWheres) {
            return $where;
        }

        foreach ($where as $whereChunk) {
            if (is_array($whereChunk)) {
                throw new InvalidWhereException();
            }
        }

        return $where;
    }
}
