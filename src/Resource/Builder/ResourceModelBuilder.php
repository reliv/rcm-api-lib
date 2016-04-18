<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Model\Resource;

/**
 * interface ResourceModelBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface ResourceModelBuilder
{
    /**
     * build
     *
     * @param string $resourceControllerKey
     *
     * @return Resource
     */
    public function build($resourceControllerKey);
}
