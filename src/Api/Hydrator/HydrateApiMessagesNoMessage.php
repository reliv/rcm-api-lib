<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Reliv\RcmApiLib\Exception\CanNotHydrate;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesNoMessage implements HydrateApiMessages
{
    /**
     * hydrate
     *
     * @param mixed       $apiMessageData
     * @param ApiMessages $apiMessages
     *
     * @return ApiMessages
     * @throws CanNotHydrate
     */
    public function __invoke($apiMessageData, ApiMessages $apiMessages)
    {
        if (!empty($apiMessageData)) {
            throw new CanNotHydrate(
                get_class($this) . ' cannot hydrate this data type'
            );
        }

        // no message, do nothing
        return $apiMessages;
    }
}
