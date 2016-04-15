<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
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
     * @var Options
     */
    protected $defaultOptions;

    /**
     * AbstractResponseFormat constructor.
     *
     * @param Options $defaultOptions
     */
    public function __construct(Options $defaultOptions)
    {
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * buildRuntimeOptions
     *
     * @param array|null $runTimeOptions
     *
     * @return Options
     */
    public function buildRuntimeOptions(array $runTimeOptions = null)
    {
        $defaultOptions = clone($this->defaultOptions);

        $defaultOptions->setFromArray($runTimeOptions);

        return $defaultOptions;
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
        $resourceUrlParams = $request->getAttribute(Route::PARAMS_NAME, []);

        if (array_key_exists($key, $resourceUrlParams)) {
            return $resourceUrlParams[$key];
        }

        return $default;
    }

}
