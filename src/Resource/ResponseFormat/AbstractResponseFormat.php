<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Model\ResourceModel;
use Reliv\RcmApiLib\Resource\Model\ResponseFormatModel;
use Reliv\RcmApiLib\Resource\Options\Options;

/**
 * Class AbstractResponseFormat
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
abstract class AbstractResponseFormat implements ResponseFormat
{
    /**
     * getOptions
     *
     * @param Request $request
     *
     * @return Options
     */
    public function getOptions(Request $request)
    {
        /** @var ResponseFormatModel $responseFormatModel */
        $responseFormatModel = $request->getAttribute(ResponseFormatModel::REQUEST_ATTRIBUTE_MODEL_RESOURCE_FORMAT);

        return $responseFormatModel->getOptions();
    }
}
