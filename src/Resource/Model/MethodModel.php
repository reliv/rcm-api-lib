<?php

namespace Reliv\RcmApiLib\Resource\Model;

/**
 * interface MethodModel
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface MethodModel
{
    /**
     * Request Attribute Id
     */
    const REQUEST_ATTRIBUTE_MODEL_METHOD = 'api-lib-resource-model-method';

    /**
     * getName
     *
     * @return string
     */
    public function getName();

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription();

    /**
     * getHttpVerb
     *
     * @return string
     */
    public function getHttpVerb();

    /**
     * getPath
     *
     * @return string
     */
    public function getPath();

    /**
     * getPreService
     *
     * @return PreServiceModel
     */
    public function getPreServiceModel();
}
