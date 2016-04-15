<?php

namespace Reliv\RcmApiLib\Resource\Config;

use Reliv\RcmApiLib\Resource\Controller\ResourceController;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Reliv\RcmApiLib\Resource\Options\Options;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ArrayConfig
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ZfArrayConfig implements Config
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceManager;

    /**
     * @var array
     */
    protected $configCache = [];

    /**
     * MainMiddleware constructor.
     *
     * @param  array                  $config
     * @param ServiceLocatorInterface $serviceManager
     */
    public function __construct(
        $config,
        ServiceLocatorInterface $serviceManager
    ) {
        $this->config = $config['Reliv\\RcmApiLib'];
        $this->serviceManager = $serviceManager;
    }

    /**
     * getDefaultConfig
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return $this->config['default'];
    }

    /**
     * getResourceControllerConfig
     *
     * @param string     $resourceControllerKey
     * @param null|mixed $default
     *
     * @return array
     */
    protected function getResourceControllerConfig($resourceControllerKey, $default = null)
    {
        if (array_key_exists($resourceControllerKey, $this->config['resourceControllers'])) {
            return $this->config['resourceControllers'][$resourceControllerKey];
        }

        return $default;
    }

    /**
     * @todo implement cache
     *
     * getResourceControllerConfigValue
     *
     * @param string $resourceControllerKey
     * @param string $key
     * @param null   $default
     *
     * @return array|null
     */
    protected function getResourceControllerConfigValue($resourceControllerKey, $key, $default = null)
    {
        $config = $this->getResourceControllerConfig($resourceControllerKey, null);

        if (empty($config)) {
            return $default;
        }

        $defaults = $this->getDefaultConfig();

        $config = array_merge($defaults[$key], $config[$key]);

        return $config;
    }

    /**
     * getAllowedMethods
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    protected function getAllowedMethods($resourceControllerKey)
    {
        return $this->getResourceControllerConfigValue($resourceControllerKey, 'allowedMethods');
    }

    /**
     * getBasePath
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->config['basePath'];
    }

    /**
     * getController
     *
     * @param string     $resourceControllerKey
     * @param null|mixed $default
     *
     * @return ResourceController|null
     */
//    public function getController($resourceControllerKey, $default = null)
//    {
//        $controllerConfig = $this->getResourceControllerConfigValue($resourceControllerKey, 'controller');
//
//        if (empty($controllerConfig) && empty($controllerConfig['service'])) {
//            return $default;
//        }
//
//        return $this->serviceManager->get($controllerConfig['service']);
//    }

    /**
     * getControllerOptions
     *
     * @param string     $resourceControllerKey
     * @param null|mixed $default
     *
     * @return Options|null
     */
//    public function getControllerOptions($resourceControllerKey, $default = null)
//    {
//        $controllerConfig = $this->getResourceControllerConfigValue($resourceControllerKey, 'controller');
//
//        if (empty($controllerConfig) && empty($controllerConfig['options'])) {
//            return $default;
//        }
//
//        return $controllerConfig['options'];
//    }

    /**
     * getFormat
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getFormat($resourceControllerKey, $default = null)
    {
        $controllerConfig = $this->getResourceControllerConfigValue($resourceControllerKey, 'controller');

        if (empty($controllerConfig) && empty($controllerConfig['formats'])) {
            return $default;
        }

        return $this->getResourceControllerConfigValue($resourceControllerKey, 'formats');
    }

    /**
     * getFormat
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getFormatOptions($resourceControllerKey)
    {
        return $this->getResourceControllerConfigValue($resourceControllerKey, 'formats');
    }

    /**
     * getFormat
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getMethods($resourceControllerKey)
    {
        return $this->getResourceControllerConfigValue($resourceControllerKey, 'methods');
    }

    /**
     * getPath
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getPath($resourceControllerKey)
    {
        return $resourceControllerKey;
    }

    /**
     * getPre
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getPre($resourceControllerKey)
    {
        return $this->getResourceControllerConfigValue($resourceControllerKey, 'pre');
    }

    /**
     * getMissingMethodStatus
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getMissingMethodStatus($resourceControllerKey)
    {
        return $this->getResourceControllerConfigValue($resourceControllerKey, 'missingMethodStatus');
    }
}
