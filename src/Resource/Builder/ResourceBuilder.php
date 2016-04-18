<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Interface ResourceBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface ResourceBuilder
{
    /**
     * getOptions
     *
     * @param string $resourceControllerKey
     * @param mixed  $default
     *
     * @return mixed|Options
     */
    public function getOptions($resourceControllerKey, $default = null);

    /**
     * getDefaultOptions
     *
     * @return Options
     */
    public function getDefaultOptions();

    /**
     * build
     *
     * @param string $resourceControllerKey
     * @param mixed  $default
     *
     * @return mixed
     */
    public function build($resourceControllerKey, $default = null);
}
