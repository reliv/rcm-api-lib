<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
     * @var array
     */
    protected $config;

    /**
     * AbstractResourceController constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function getNewModel()
    {

    }

    protected function hydrateModel(Request $request)
    {

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
        $resourceUrlParams = $request->getAttribute('resource-route-params', []);

        if (array_key_exists($key, $resourceUrlParams)) {
            return $resourceUrlParams[$key];
        }

        return $default;
    }

}
