<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Exception\OptionException;
use Reliv\RcmApiLib\Resource\Options\GenericOptions;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class ConfigResource
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ConfigResource implements Resource
{
    /**
     * @var string
     */
    protected $resourceControllerKey;

    /**
     * @var Options
     */
    protected $defaultOptions;

    /**
     * @var Options
     */
    protected $resourceOptions;

    /**
     * @var string
     */
    protected $optionNotFound = '__OPTION_NOT_FOUND__';

    /**
     * @var array of Methods
     */
    protected $validMethods = [];

    /**
     * @var array of Options
     */
    protected $preOptions = [];

    /**
     * @var Options
     */
    protected $controllerOptions;

    /**
     * @var Options
     */
    protected $responseFormatOptions;

    /**
     * @var
     */
    protected $routeOptions;

    /**
     * Resource constructor.
     *
     * @param string  $resourceControllerKey
     * @param Options $defaultOptions
     * @param Options $resourcesOptions
     *
     * @throws OptionException
     */
    public function __construct(
        $resourceControllerKey,
        Options $defaultOptions,
        Options $resourcesOptions
    ) {
        $this->resourceControllerKey = $resourceControllerKey;
        $this->defaultOptions = $defaultOptions;

        if (!$resourcesOptions->has($resourceControllerKey)) {
            throw new OptionException("No options exist for {$resourceControllerKey}");
        }

        $this->resourceOptions = $resourcesOptions->getOptions($resourceControllerKey);
    }

    /**
     * buildValue
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    protected function buildValue($key, $default = null)
    {
        $defaultValue = $this->defaultOptions->get($key, $default);
        $value = $this->resourceOptions->get($key, $defaultValue);

        return $value;
    }

    /**
     * buildOptions
     *
     * @param $key
     *
     * @return Options
     */
    protected function buildOptions($key)
    {
        $array = $this->buildValue($key, []);

        return new GenericOptions($array);
    }

    /**
     * getAllowedMethods
     *
     * @return array|null
     */
    public function getAllowedMethods()
    {
        return $this->resourceOptions->get('allowedMethods', []);
    }

    /**
     * getControllerOptions
     *
     * @return string|null
     */
    public function getControllerService()
    {
        return $this->buildValue('controllerService');
    }

    /**
     * getControllerOptions
     *
     * @return Options
     */
    public function getControllerOptions()
    {
        if (!empty($this->controllerOptions)) {
            return $this->controllerOptions;
        }
        $this->controllerOptions = $this->buildOptions('controllerOptions');

        return $this->controllerOptions;
    }

    /**
     * getMethods
     *
     * @return array
     */
    public function getMethods()
    {
        if (!empty($this->validMethods)) {
            return $this->validMethods;
        }

        $methods = $this->buildValue('methods', []);
        $allowedMethods = $this->getAllowedMethods();

        foreach ($allowedMethods as $allowedMethod) {
            if (array_key_exists($allowedMethod, $methods)) {
                $methodOptions = new GenericOptions($methods[$allowedMethod]);
                $this->validMethods[$allowedMethod] = new ConfigMethod($methodOptions);
            }
        }

        return $this->validMethods;
    }

    /**
     * getMethod
     *
     * @param string $name
     *
     * @return Method|null
     */
    public function getMethod($name)
    {
        $methods = $this->getMethods();

        if (array_key_exists($name, $methods)) {
            return new ConfigMethod($methods[$name]);
        }

        return null;
    }

    /**
     * hasMethod
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasMethod($name)
    {
        $method = $this->getMethod($name);

        return !empty($method);
    }

    /**
     * getMissingMethodStatus
     *
     * @return int
     */
    public function getMissingMethodStatus()
    {
        return $this->buildValue('missingMethodStatus', 404);
    }

    /**
     * getPath
     *
     * @return string
     */
    public function getPath()
    {
        return $this->resourceOptions->get('path', $this->resourceControllerKey);
    }

    /**
     * getPreServices
     *
     * @return array
     */
    public function getPreServices()
    {
        return $this->buildValue('preServices', []);
    }

    /**
     * hasPreService
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasPreService($name)
    {
        return in_array($name, $this->getPreServices());
    }

    /**
     * getPreOptions
     *
     * @param string $name
     *
     * @return Options
     */
    public function getPreOptions($name)
    {
        if (array_key_exists($name, $this->preOptions)) {
            return $this->preOptions;
        }

        $array = $this->buildValue('preOptions', []);

        $options = new GenericOptions();

        if (array_key_exists($name, $array)) {
            $options->setFromArray($array[$name]);
        }

        $this->preOptions[$name] = $options;

        return $this->preOptions[$name];
    }

    /**
     * getResponseFormatService
     *
     * @return string|null
     */
    public function getResponseFormatService()
    {
        return $this->buildValue('responseFormatService');
    }

    /**
     * getResponseFormatOptions
     *
     * @return Options
     */
    public function getResponseFormatOptions()
    {
        if (!empty($this->responseFormatOptions)) {
            return $this->responseFormatOptions;
        }
        $this->responseFormatOptions = $this->buildOptions('responseFormatOptions');

        return $this->responseFormatOptions;
    }

    /**
     * getRouteService
     *
     * @return string|null
     */
    public function getRouteService()
    {
        return $this->buildValue('routeService');
    }

    /**
     * getRouteOptions
     *
     * @return Options
     */
    public function getRouteOptions()
    {
        if (!empty($this->routeOptions)) {
            return $this->routeOptions;
        }
        $this->routeOptions = $this->buildOptions('routeOptions');

        return $this->routeOptions;
    }
}
