<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Model\ResourceModel;

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
     * @param string $resourceKey
     *
     * @return ResourceModel
     */
    public function build($resourceKey);
}
