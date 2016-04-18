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
interface Resource
{
    /**
     * REQUEST_ATTRIBUTE
     */
    const REQUEST_ATTRIBUTE = 'api-lib-resource-model';

    /**
     * getAllowedMethods
     *
     * @return array
     */
    public function getAllowedMethods();

    /**
     * getControllerService
     *
     * @return string|null
     */
    public function getControllerService();

    /**
     * getControllerOptions
     *
     * @return Options
     */
    public function getControllerOptions();

    /**
     * getMethods
     *
     * @return array
     */
    public function getMethods();

    /**
     * getMethod
     *
     * @param string $name
     *
     * @return Method|null
     */
    public function getMethod($name);

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
    public function getMissingMethodStatus();

    /**
     * getPath
     *
     * @return string
     */
    public function getPath();

    /**
     * getPreServices
     *
     * @return array
     */
    public function getPreServices();

    /**
     * getPreService
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasPreService($name);

    /**
     * getPreOptions
     *
     * @param string $name
     *
     * @return Options
     */
    public function getPreOptions($name);

    /**
     * getResponseFormatService
     *
     * @return string|null
     */
    public function getResponseFormatService();

    /**
     * getResponseFormatOptions
     *
     * @return Options
     */
    public function getResponseFormatOptions();
}
