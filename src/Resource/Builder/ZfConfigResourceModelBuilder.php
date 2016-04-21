<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Model\BaseControllerModel;
use Reliv\RcmApiLib\Resource\Model\BaseMethodModel;
use Reliv\RcmApiLib\Resource\Model\BasePreServiceModel;
use Reliv\RcmApiLib\Resource\Model\BaseResourceModel;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ZFConfigResourceModelBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ZfConfigResourceModelBuilder extends ZfConfigAbstractResourceModelBuilder implements ResourceModelBuilder
{
    /**
     * @var array
     */
    protected $resourceModels = [];

    /**
     * getMethodsAllowed
     *
     * @param $resourceKey
     *
     * @return mixed
     */
    protected function getMethodsAllowed($resourceKey)
    {
        return $this->getResourceValue($resourceKey, 'methodsAllowed', []);
    }

    /**
     * buildPreServiceModel
     *
     * @param $preServiceNames
     * @param $preServiceOptionsArrays
     *
     * @return BasePreServiceModel
     */
    protected function buildPreServiceModel($preServiceNames, $preServiceOptionsArrays)
    {
        $preServices = [];
        foreach ($preServiceNames as $serviceAlias => $preServiceName) {
            $preServices[$serviceAlias] = $this->serviceManager->get($preServiceName);
        }

        $preServiceOptions = [];
        foreach ($preServiceOptionsArrays as $serviceAlias => $preServiceOptionsArray) {
            $preServiceOptions[$serviceAlias] = new GenericOptions($preServiceOptionsArray);
        }

        return new BasePreServiceModel(
            $preServices,
            $preServiceOptions
        );
    }

    /**
     * buildMethodModels
     *
     * @param string $resourceKey
     *
     * @return array
     */
    protected function buildMethodModels($resourceKey)
    {
        $defaultMethods = $this->getDefaultValue('methods', []);
        $resourceMethods = $this->getResourceValue($resourceKey, 'methods', []);

        $methods = array_merge($defaultMethods, $resourceMethods);

        $allowedMethods = $this->getMethodsAllowed($resourceKey);
        $validMethods = [];

        foreach ($allowedMethods as $allowedMethod) {
            if (array_key_exists($allowedMethod, $methods)) {
                $methods[$allowedMethod]['name'] = $allowedMethod;
                $methodOptions = new GenericOptions($methods[$allowedMethod]);

                $preServiceNames = $methodOptions->get('preServiceNames', []);
                $preServiceOptionsArrays = $methodOptions->get('preServiceOptions', []);

                $preService = $this->buildPreServiceModel(
                    $preServiceNames,
                    $preServiceOptionsArrays
                );

                $validMethods[$allowedMethod] = new BaseMethodModel(
                    $methodOptions->get('name'),
                    $methodOptions->get('description'),
                    $methodOptions->get('httpVerb'),
                    $methodOptions->get('path'),
                    $preService
                );
            }
        }

        return $validMethods;
    }

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

        // ControllerModel
        $controllerServiceAlias = $this->buildValue(
            $resourceKey,
            'controllerServiceName',
            'UNDEFINED'
        );

        $controllerService = $this->serviceManager->get(
            $controllerServiceAlias
        );

        $controllerOptions = $this->buildOptions(
            $resourceKey,
            'controllerOptions'
        );

        $controllerModel = new BaseControllerModel(
            $controllerServiceAlias,
            $controllerService,
            $controllerOptions
        );

        // Methods
        $methodsAllowed = $this->getMethodsAllowed($resourceKey);
        $methodModels = $this->buildMethodModels($resourceKey);
        $path = $this->getResourceValue($resourceKey, 'path');

        $preServiceModel = $this->buildPreServiceModel(
            $this->buildValue($resourceKey, 'preServiceNames', []),
            $this->buildValue($resourceKey, 'preServiceOptions', [])
        );

        $methodMissingStatus = $this->buildValue($resourceKey, 'methodMissingStatus', 404);

        $resourceModel = new BaseResourceModel(
            $controllerModel,
            $methodsAllowed,
            $methodModels,
            $path,
            $preServiceModel,
            $methodMissingStatus
        );

        $this->resourceModels[$resourceKey] = $resourceModel;

        return $this->resourceModels[$resourceKey];
    }
}
