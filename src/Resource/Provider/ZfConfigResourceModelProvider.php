<?php

namespace Reliv\RcmApiLib\Resource\Provider;

use Reliv\RcmApiLib\Resource\Model\BaseControllerModel;
use Reliv\RcmApiLib\Resource\Model\BaseMethodModel;
use Reliv\RcmApiLib\Resource\Model\BaseResourceModel;
use Reliv\RcmApiLib\Resource\Model\BaseServiceModelCollection;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Reliv\RcmApiLib\Resource\Model\ServiceModelCollection;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ZFConfigResourceModelProvider
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ZfConfigResourceModelProvider extends ZfConfigAbstractResourceModelProvider implements ResourceModelProvider
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
     * buildServiceModelCollection
     *
     * @param array $serviceNames
     * @param array $serviceOptionsArrays
     *
     * @return ServiceModelCollection
     */
    protected function buildServiceModelCollection($serviceNames, $serviceOptionsArrays)
    {
        $preServices = [];
        foreach ($serviceNames as $serviceAlias => $serviceName) {
            $preServices[$serviceAlias] = $this->serviceManager->get($serviceName);
        }

        $preServiceOptions = [];
        foreach ($serviceOptionsArrays as $serviceAlias => $serviceOptionsArray) {
            $preServiceOptions[$serviceAlias] = new GenericOptions($serviceOptionsArray);
        }

        return new BaseServiceModelCollection(
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

                $preServiceModel = $this->buildServiceModelCollection(
                    $methodOptions->get('preServiceNames', []),
                    $methodOptions->get('preServiceOptions', [])
                );

                $postServiceModel = $this->buildServiceModelCollection(
                    $methodOptions->get('postServiceNames', []),
                    $methodOptions->get('postServiceOptions', [])
                );

                $options = $methodOptions->getOptions('options');

                $validMethods[$allowedMethod] = new BaseMethodModel(
                    $methodOptions->get('name'),
                    $methodOptions->get('description'),
                    $methodOptions->get('httpVerb'),
                    $methodOptions->get('path'),
                    $preServiceModel,
                    $postServiceModel,
                    $options
                );
            }
        }

        return $validMethods;
    }

    /**
     * get
     *
     * @param string $resourceKey
     *
     * @return ResourceModel
     */
    public function get($resourceKey)
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

        $preServiceModel = $this->buildServiceModelCollection(
            $this->buildValue($resourceKey, 'preServiceNames', []),
            $this->buildValue($resourceKey, 'preServiceOptions', [])
        );

        $postServiceModel = $this->buildServiceModelCollection(
            $this->buildValue($resourceKey, 'postServiceNames', []),
            $this->buildValue($resourceKey, 'postServiceOptions', [])
        );

        $finalServiceModel = $this->buildServiceModelCollection(
            $this->buildValue($resourceKey, 'finalServiceNames', []),
            $this->buildValue($resourceKey, 'finalServiceOptions', [])
        );

        $options = $this->buildOptions($resourceKey, 'options');

        $resourceModel = new BaseResourceModel(
            $controllerModel,
            $methodsAllowed,
            $methodModels,
            $path,
            $preServiceModel,
            $postServiceModel,
            $finalServiceModel,
            $options
        );

        $this->resourceModels[$resourceKey] = $resourceModel;

        return $this->resourceModels[$resourceKey];
    }
}
