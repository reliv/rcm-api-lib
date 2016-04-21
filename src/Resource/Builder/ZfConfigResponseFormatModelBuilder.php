<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Model\BaseResponseFormatModel;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ZfConfigResponseFormatModelBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ZfConfigResponseFormatModelBuilder extends ZfConfigAbstractResourceModelBuilder implements ResponseFormatModelBuilder
{
    /**
     * @var array
     */
    protected $resourceModels = [];

    /**
     * build
     *
     * @param string $resourceKey
     *
     * @return ResourceModel
     */
    public function build($resourceKey)
    {
        if (array_key_exists($resourceKey, $this->resourceModels)) {
            return $this->resourceModels[$resourceKey];
        }

        // responseFormatModel
        $responseFormatServiceAlias = $this->buildValue(
            $resourceKey,
            'responseFormatServiceName',
            'UNDEFINED'
        );

        $responseFormatService = $this->serviceManager->get(
            $responseFormatServiceAlias
        );

        $responseFormatOptions = $this->buildOptions(
            $resourceKey,
            'responseFormatOptions'
        );
        $responseFormatModel = new BaseResponseFormatModel(
            $responseFormatServiceAlias,
            $responseFormatService,
            $responseFormatOptions
        );

        $this->resourceModels[$resourceKey] = $responseFormatModel;

        return $this->resourceModels[$resourceKey];
    }
}
