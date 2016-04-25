<?php

namespace Reliv\RcmApiLib\Resource\Provider;

use Reliv\RcmApiLib\Resource\Model\ResourceModel;

/**
 * interface ResourceModelProvider
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface ResourceModelProvider
{
    /**
     * get
     *
     * @param string $resourceKey
     *
     * @return ResourceModel
     */
    public function get($resourceKey);
}
