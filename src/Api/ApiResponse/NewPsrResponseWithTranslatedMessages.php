<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Api\Options;
use Reliv\RcmApiLib\Http\PsrApiResponse;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class NewPsrResponseWithTranslatedMessages
{
    const OPTIONS_ENCODING = 'jsonEncodingOptions';

    /**
     * @var WithApiMessage
     */
    protected $withApiMessage;
    /**
     * @var WithTranslatedApiMessages
     */
    protected $withTranslatedApiMessages;

    /**
     * @param WithApiMessage            $withApiMessage
     * @param WithTranslatedApiMessages $withTranslatedApiMessages
     */
    public function __construct(
        WithApiMessage $withApiMessage,
        WithTranslatedApiMessages $withTranslatedApiMessages
    ) {
        $this->withApiMessage = $withApiMessage;
        $this->withTranslatedApiMessages = $withTranslatedApiMessages;
    }

    /**
     * @param mixed $data
     * @param int   $statusCode
     * @param null  $apiMessageData
     * @param array $headers
     * @param array $options
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponseInterface|PsrApiResponse
     */
    public function __invoke(
        $data,
        $statusCode = 200,
        $apiMessageData = null,
        $headers = [],
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
            $headers,
            $encodingOptions
        );

        if (empty($apiMessageData)) {
            return $this->withTranslatedApiMessages->__invoke(
                $apiResponse
            );
        }

        $this->withApiMessage->__invoke(
            $apiResponse,
            $apiMessageData
        );

        return $this->withTranslatedApiMessages->__invoke(
            $apiResponse
        );
    }
}
