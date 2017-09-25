<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Psr\Http\Message\ResponseInterface;
use Reliv\RcmApiLib\Api\Options;
use Reliv\RcmApiLib\Http\PsrApiResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class NewPsr extends NewAbstract
{
    const OPTIONS_ENCODING = 'jsonEncodingOptions';

    /**
     * @param WithApiMessage            $withApiMessage
     * @param WithTranslatedApiMessages $withTranslatedApiMessages
     */
    public function __construct(
        WithApiMessage $withApiMessage,
        WithTranslatedApiMessages $withTranslatedApiMessages
    ) {
        parent::__construct($withApiMessage, $withTranslatedApiMessages);
    }

    /**
     * @param ResponseInterface $response
     * @param                   $data
     * @param int               $statusCode
     * @param null              $apiMessagesData
     * @param array             $options
     *
     * @return PsrApiResponse
     */
    public function __invoke(
        ResponseInterface $response,
        $data,
        $statusCode = 200,
        $apiMessagesData = null,
        array $options = []
    ) {
        $encodingOptions = Options::getOption(
            $options,
            self::OPTIONS_ENCODING,
            0
        );

        $apiResponse = new PsrApiResponse(
            $data,
            [],
            $statusCode,
            $response->getHeaders(),
            $encodingOptions
        );

        return $this->getApiResponse(
            $apiResponse,
            $data,
            $statusCode,
            $apiMessagesData
        );
    }
}
