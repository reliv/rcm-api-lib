<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Model\ConfigResource;
use Reliv\RcmApiLib\Resource\Model\Resource;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class ConfigResourceModelBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ConfigResourceModelBuilder
{
    /**
     * @var
     */
    protected $resourceModels = [];

    /**
     * ConfigResourceModelBuilder constructor.
     *
     * @param Options $defaultOptions
     * @param Options $resourcesOptions
     */
    public function __construct(
        Options $defaultOptions,
        Options $resourcesOptions
    ) {
        $this->defaultOptions = $defaultOptions;
        $this->resourceOptions = $resourcesOptions;
    }

    /**
     * build
     *
     * @param string $resourceControllerKey
     *
     * @return Resource
     */
    public function build($resourceControllerKey)
    {
        if (array_key_exists($resourceControllerKey, $this->resourceModels)) {
            return $this->resourceModels[$resourceControllerKey];
        }

        $this->resourceModels[$resourceControllerKey] = new ConfigResource(
            $resourceControllerKey,
            $this->defaultOptions,
            $this->resourceOptions
        );

        return $this->resourceModels[$resourceControllerKey];
    }
}
