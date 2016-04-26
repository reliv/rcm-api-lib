<?php

namespace Reliv\RcmApiLib\Resource\Provider;

use Reliv\RcmApiLib\Resource\Model\ServiceModelCollection;

/**
 * interface ModelProvider
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface ModelProvider
{
    /**
     * get
     *
     * @return ServiceModelCollection
     */
    public function get();
}
