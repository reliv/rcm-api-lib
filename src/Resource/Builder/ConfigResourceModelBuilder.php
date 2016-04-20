<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Model\BaseControllerModel;
use Reliv\RcmApiLib\Resource\Model\BaseMethodModel;
use Reliv\RcmApiLib\Resource\Model\BasePreServiceModel;
use Reliv\RcmApiLib\Resource\Model\BaseResourceModel;
use Reliv\RcmApiLib\Resource\Model\BaseResponseFormatModel;
use Reliv\RcmApiLib\Resource\Model\BaseRouteModel;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Reliv\RcmApiLib\Resource\Options\Options;
use Zend\ServiceManager\ServiceLocatorInterface;

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
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;

    /**
     * @var Options
     */
    protected $defaultOptions;

    /**
     * @var Options
     */
    protected $resourcesOptions;

    /**
     * @var
     */
    protected $resourceModels = [];

    /**
     * ConfigResourceModelBuilder constructor.
     *
     * @param ServiceLocatorInterface $serviceManager
     * @param Options                 $defaultOptions
     * @param Options                 $resourcesOptions
     */
    public function __construct(
        ServiceLocatorInterface $serviceManager,
        Options $defaultOptions,
        Options $resourcesOptions
    ) {
        $this->serviceManager = $serviceManager;
        $this->defaultOptions = $defaultOptions;
        $this->resourcesOptions = $resourcesOptions;
    }

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
     * getResourceValue
     *
     * @param string $resourceKey
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    protected function getResourceValue($resourceKey, $key, $default = null)
    {
        $resourceOptions = $this->resourcesOptions->getOptions($resourceKey);
        $value = $resourceOptions->get($key, $default);

        return $value;
    }

    /**
     * getDefaultValue
     *
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    protected function getDefaultValue($key, $default = null)
    {
        return $this->defaultOptions->get($key, $default);
    }

    /**
     * buildValue
     *
     * @param string $resourceKey
     * @param string $key
     * @param null   $default
     *
     * @return mixed
     */
    protected function buildValue($resourceKey, $key, $default = null)
    {
        $defaultValue = $this->getDefaultValue($key, $default);

        return $this->getResourceValue($resourceKey, $key, $defaultValue);
    }

    /**
     * buildOptions
     *
     * @param string $resourceKey
     * @param string $key
     *
     * @return GenericOptions
     */
    protected function buildOptions($resourceKey, $key)
    {
        $array = $this->buildValue($resourceKey, $key, []);

        return new GenericOptions($array);
    }

    /**
     * buildResourceOptions
     *
     * @param $resourceKey
     *
     * @return void
     */
    protected function buildResourceOptions($resourceKey)
    {

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
        $resourceMethods = $this->getResourceValue('methods', []);

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
        $controllerService = $this->serviceManager->get(
            $this->buildValue(
                $resourceKey,
                'controllerServiceName',
                'UNDEFINED'
            )
        );

        $controllerOptions = $this->buildOptions(
            $resourceKey,
            'controllerOptions'
        );

        $controllerModel = new BaseControllerModel(
            $controllerService,
            $controllerOptions
        );

        $methodsAllowed = $this->getMethodsAllowed($resourceKey);
        $methodModels = $this->buildMethodModels($resourceKey);
        $path = $this->getResourceValue($resourceKey, 'path');

        // responseFormatModel
        $preServiceModel = $this->buildPreServiceModel(
            $this->buildValue($resourceKey, 'preServiceNames', []),
            $this->buildValue($resourceKey, 'preServiceOptions', [])
        );

        // responseFormatModel
        $responseFormatService = $this->serviceManager->get(
            $this->buildValue(
                $resourceKey,
                'responseFormatServiceName',
                'UNDEFINED'
            )
        );

        $responseFormatOptions = $this->buildOptions(
            $resourceKey,
            'responseFormatOptions'
        );
        $responseFormatModel = new BaseResponseFormatModel(
            $responseFormatService,
            $responseFormatOptions
        );

        $routeService = $this->serviceManager->get(
            $this->buildValue(
                $resourceKey,
                'routeServiceName',
                'UNDEFINED'
            )
        );

        $routeOptions = $this->buildOptions(
            $resourceKey,
            'routeOptions'
        );
        $routeModel = new BaseRouteModel(
            $routeService,
            $routeOptions
        );

        $methodMissingStatus = $this->buildValue('methodMissingStatus', 404);

        $resourceModel = new BaseResourceModel(
            $controllerModel,
            $methodsAllowed,
            $methodModels,
            $path,
            $preServiceModel,
            $responseFormatModel,
            $routeModel,
            $methodMissingStatus
        );

        $this->resourceModels[$resourceKey] = $resourceModel;

        return $this->resourceModels[$resourceKey];
    }
}
