<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Options\DefaultResourceControllerOptions;
use Reliv\RcmApiLib\Resource\Options\Options;
use Reliv\RcmApiLib\Resource\Options\ResourceControllersOptions;

/**
 * Class AbstractControllerBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class AbstractControllerBuilder
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
     * getControllerOptions
     *
     * @param string $resourceControllerKey
     *
     * @return Options
     */
    public function getControllerOptions($resourceControllerKey, $default = null)
    {
        if ($this->resourceControllersOptions->has($resourceControllerKey)) {
            return $this->resourceControllersOptions->getOptions($resourceControllerKey);
        }

        return $default;
    }

    /**
     * getDefaultControllerOptions
     *
     * @return DefaultResourceControllerOptions
     */
    public function getDefaultControllerOptions()
    {
        return $this->defaultResourceControllerOptions;
    }
}
