<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Interface Resource
 *
 * PHP version 5
 *
 * @category  Reliv
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface ResourceModel
{
    /**
     * Request Attribute Id
     */
    const REQUEST_ATTRIBUTE_MODEL_RESOURCE = 'api-lib-resource-model-resource';
    
    /**
     * getControllerModel
     *
     * @return ServiceModel
     */
    public function getControllerModel();

    /**
     * getAllowedMethods
     *
     * @return array
     */
    public function getMethodsAllowed();

    /**
     * getMethods
     *
     * @return array
     */
    public function getMethodModels();

    /**
     * getMethod
     *
     * @param string $name
     *
     * @return MethodModel|null
     */
    public function getMethodModel($name);

    /**
     * hasMethod
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasMethod($name);

    /**
     * getMissingMethodStatus
     *
     * @return int
     */
    public function getMethodMissingStatus();

    /**
     * getPath
     *
     * @return string
     */
    public function getPath();

    /**
     * getPreServiceModel
     *
     * @return ServiceModelCollection
     */
    public function getPreServiceModel();

    /**
     * getPostServiceModel
     *
     * @return ServiceModelCollection
     */
    public function getPostServiceModel();

    /**
     * getFinalServiceModel
     *
     * @return ServiceModelCollection
     */
    public function getFinalServiceModel();
}
