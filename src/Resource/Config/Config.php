<?php

namespace Reliv\RcmApiLib\Resource\Config;

/**
 * Class Config
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Config
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface Config
{
    /**
     * getBasePath
     *
     * @return string
     */
    public function getBasePath();

    /**
     * getController
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getController($resourceControllerKey);

    /**
     * getFormat
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getFormat($resourceControllerKey);

    /**
     * getFormat
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getMethods($resourceControllerKey);

    /**
     * getPath
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getPath($resourceControllerKey);

    /**
     * getPre
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getPre($resourceControllerKey);

    /**
     * getMissingMethodStatus
     *
     * @param string $resourceControllerKey
     *
     * @return array|null
     */
    public function getMissingMethodStatus($resourceControllerKey);
}
