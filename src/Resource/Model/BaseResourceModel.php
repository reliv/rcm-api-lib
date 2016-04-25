<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class BaseResourceModel
 *
 * PHP version 5
 *
 * @category  Reliv
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class BaseResourceModel implements ResourceModel
{
    /**
     * @var string
     */
    protected $resourceKey;
    /**
     * @var ControllerModel
     */
    protected $controllerModel;

    /**
     * @var array ['{methodName}' => {MethodModel}]
     */
    protected $methodModels;

    /**
     * @var array
     */
    protected $methodsAllowed = [];

    /**
     * @var int
     */
    protected $methodMissingStatus = 404;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var ServiceModelCollection
     */
    protected $preServiceModel;

    /**
     * @var ServiceModelCollection
     */
    protected $postServiceModel;

    /**
     * @var ServiceModelCollection
     */
    protected $finalServiceModel;

    /**
     * @param ControllerModel        $controllerModel
     * @param array                  $methodsAllowed
     * @param array                  $methodModels
     * @param string                 $path
     * @param ServiceModelCollection $preServiceModel
     * @param ServiceModelCollection $postServiceModel
     * @param ServiceModelCollection $finalServiceModel
     * @param int                    $methodMissingStatus
     */
    public function __construct(
        ControllerModel $controllerModel,
        array $methodsAllowed,
        array $methodModels,
        $path,
        ServiceModelCollection $preServiceModel,
        ServiceModelCollection $postServiceModel,
        ServiceModelCollection $finalServiceModel,
        $methodMissingStatus = 404
    ) {
        $this->controllerModel = $controllerModel;
        $this->methodsAllowed = $methodsAllowed;
        foreach ($methodModels as $methodName => $methodModel) {
            $this->addMethod($methodName, $methodModel);
        }
        $this->path = $path;
        $this->preServiceModel = $preServiceModel;
        $this->postServiceModel = $postServiceModel;
        $this->finalServiceModel = $finalServiceModel;
        $this->methodMissingStatus = $methodMissingStatus;
    }

    /**
     * addMethod
     *
     * @param string      $methodName
     * @param MethodModel $methodModel
     *
     * @return void
     */
    protected function addMethod($methodName, MethodModel $methodModel)
    {
        $this->methodModels[$methodName] = $methodModel;
    }

    /**
     * getControllerModel
     *
     * @return ServiceModel
     */
    public function getControllerModel()
    {
        return $this->controllerModel;
    }

    /**
     * getAllowedMethods
     *
     * @return array
     */
    public function getMethodsAllowed()
    {
        return $this->methodsAllowed;
    }

    /**
     * getMethods
     *
     * @return array MethodModel
     */
    public function getMethodModels()
    {
        return $this->methodModels;
    }

    /**
     * getMethod
     *
     * @param string $name
     *
     * @return MethodModel|null
     */
    public function getMethodModel($name)
    {
        if ($this->hasMethod($name)) {
            return $this->methodModels[$name];
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
        return array_key_exists($name, $this->methodModels);
    }

    /**
     * getMissingMethodStatus
     *
     * @return int
     */
    public function getMethodMissingStatus()
    {
        return (int)$this->methodMissingStatus;
    }

    /**
     * getPath
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * getPreServiceModel
     *
     * @return ServiceModelCollection
     */
    public function getPreServiceModel()
    {
        return $this->preServiceModel;
    }

    /**
     * getPostServiceModel
     *
     * @return ServiceModelCollection
     */
    public function getPostServiceModel()
    {
        return $this->postServiceModel;
    }

    /**
     * getFinalServiceModel
     *
     * @return ServiceModelCollection
     */
    public function getFinalServiceModel()
    {
        return $this->finalServiceModel;
    }
}
