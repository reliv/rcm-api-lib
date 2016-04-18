<?php

namespace Reliv\RcmApiLib\Resource\Model;

use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * interface Method
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface Method
{
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
     * @return array
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
}
