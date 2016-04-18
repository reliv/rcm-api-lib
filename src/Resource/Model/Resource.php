<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Exception\OptionException;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class Resource
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class Resource
{
    /**
     * @var string
     */
    protected $resourceControllerKey;

    /**
     * @var Options
     */
    protected $defaultResourceControllerOptions;

    /**
     * @var Options
     */
    protected $resourceOptions;

    /**
     * @var string
     */
    protected $optionNotFound = '__OPTION_NOT_FOUND__';

    /**
     * Resource constructor.
     *
     * @param string  $resourceControllerKey
     * @param Options $defaultResourceControllerOptions
     * @param Options $resourceControllersOptions
     *
     * @throws OptionException
     */
    public function __construct(
        $resourceControllerKey,
        Options $defaultResourceControllerOptions,
        Options $resourceControllersOptions
    ) {
        $this->resourceControllerKey = $resourceControllerKey;
        $this->defaultResourceControllerOptions = $defaultResourceControllerOptions;

        if (!$resourceControllersOptions->has($resourceControllerKey)) {
            throw new OptionException("No options exist for {$resourceControllerKey}");
        }

        $this->resourceOptions = $resourceControllersOptions->getOptions($resourceControllerKey);
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
        $defaultValue = $this->defaultResourceControllerOptions->get($key, $default);
        $value = $this->resourceOptions->get($key, $defaultValue);

        return $value;
    }

    /**
     * getAllowedMethods
     *
     * @return array|null
     */
    public function getAllowedMethods()
    {
        $value = $this->resourceOptions->get('allowedMethods', []);

        return $value;
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
     * @return array|null
     */
    public function getControllerOptions()
    {
        return $this->buildValue('controllerOptions', []);
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
     * @return array|null
     */
    public function getResponseFormatOptions()
    {
        return $this->buildValue('responseFormatOptions', []);
    }

    /**
     * getMethods
     *
     * @return void
     */
    public function getMethods()
    {
        $methods =  $this->buildValue('methods', []);
        $allowedMethods = $this->getAllowedMethods();

        $finalMethods = [];

        
    }

    public function getMethod($name, $default = null)
    {

    }

    public function getPath()
    {

    }

    public function getPreServiceNames()
    {

    }

    public function getMissingMethodStatus()
    {

    }
}
