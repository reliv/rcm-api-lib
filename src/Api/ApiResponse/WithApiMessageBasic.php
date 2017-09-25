<?php

namespace Reliv\RcmApiLib\Api\ApiResponse;

use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Hydrator\ApiMessageApiMessagesHydrator;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class WithApiMessageBasic
{
    /**
     * @param ApiMessageApiMessagesHydrator $hydrator
     */
    public function __construct(
        ApiMessageApiMessagesHydrator $hydrator
    ) {
        $this->hydrator = $hydrator;
    }

    /**
     * @param ApiResponseInterface $apiResponse
     * @param mixed                $apiMessagesData
     *
     * @return ApiResponseInterface
     */
    public function __invoke(
        ApiResponseInterface $apiResponse,
        $apiMessagesData
    ): ApiResponseInterface {
        $apiMessages = $apiResponse->getApiMessages();

        $apiMessages = $this->hydrator->hydrate($apiMessagesData, $apiMessages);

        $apiResponse->setApiMessages(
            $apiMessages
        );

        return $apiResponse;
    }
}
