<?php

namespace Reliv\RcmApiLib\Resource\Builder;

use Reliv\RcmApiLib\Resource\Model\ResponseFormatModel;

/**
 * interface ResponseFormatModelBuilder
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface ResponseFormatModelBuilder
{
    /**
     * build
     *
     * @param string $resourceKey
     *
     * @return ResponseFormatModel
     */
    public function build($resourceKey);
}
