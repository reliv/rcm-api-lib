<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Api\Hydrator\HydrateApiMessages;
use Reliv\RcmApiLib\Http\ApiResponseInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithApiMessageBasic implements WithApiMessage
{
    /**
     * @var HydrateApiMessages
     */
    protected $hydrate;

    /**
     * @param HydrateApiMessages $hydrate
     */
    public function __construct(
        HydrateApiMessages $hydrate
    ) {
        $this->hydrate = $hydrate;
    }

    /**
     * @param ApiResponseInterface $apiResponse
     * @param mixed                $apiMessageData
     *
     * @return ApiResponseInterface
     */
    public function __invoke(
        ApiResponseInterface $apiResponse,
        $apiMessageData
    ): ApiResponseInterface {
        $apiMessages = $apiResponse->getApiMessages();

        $apiMessages = $this->hydrate->__invoke($apiMessageData, $apiMessages);

        $apiResponse->setApiMessages(
            $apiMessages
        );

        return $apiResponse;
    }
}
