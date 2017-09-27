<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Reliv\RcmApiLib\Exception\CanNotHydrate;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface HydrateApiMessages
{
    /**
     * hydrate - Hydrate ApiResponse with data
     * - On Success - return ApiResponse
     * - On Fail    - ApiMessagesHydratorException
     *
     * @param mixed       $apiMessageData
     * @param ApiMessages $apiMessages
     *
     * @return ApiMessages
     * @throws CanNotHydrate
     */
    public function __invoke($apiMessageData, ApiMessages $apiMessages);
}
