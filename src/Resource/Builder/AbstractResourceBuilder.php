<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Options\DefaultResourceControllerOptions;
use Reliv\RcmApiLib\Resource\Options\Options;
use Reliv\RcmApiLib\Resource\Options\ResourceControllersOptions;

/**
 * Class AbstractResourceBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
abstract class AbstractResourceBuilder implements ResourceBuilder
{
    /**
     * @var DefaultResourceControllerOptions
     */
    protected $defaultResourceControllerOptions;

    /**
     * @var ResourceControllersOptions
     */
    protected $resourceControllersOptions;

    /**
     * AbstractControllerBuilder constructor.
     *
     * @param DefaultResourceControllerOptions $defaultResourceControllerOptions
     * @param ResourceControllersOptions       $resourceControllersOptions
     */
    public function __construct(
        DefaultResourceControllerOptions $defaultResourceControllerOptions,
        ResourceControllersOptions $resourceControllersOptions
    ) {
        $this->defaultResourceControllerOptions = $defaultResourceControllerOptions;
        $this->resourceControllersOptions = $resourceControllersOptions;
    }

    /**
     * getOptions
     *
     * @param string $resourceControllerKey
     * @param mixed   $default
     *
     * @return mixed|Options
     */
    public function getOptions($resourceControllerKey, $default = null)
    {
        if ($this->resourceControllersOptions->has($resourceControllerKey)) {
            return $this->resourceControllersOptions->getOptions($resourceControllerKey);
        }

        return $default;
    }

    /**
     * getDefaultOptions
     *
     * @return Options
     */
    public function getDefaultOptions()
    {
        return $this->defaultResourceControllerOptions;
    }
}
