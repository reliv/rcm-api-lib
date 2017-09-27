<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Reliv\RcmApiLib\Exception\CanNotHydrate;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesApiMessage implements HydrateApiMessages
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
        if (!$apiMessageData instanceof ApiMessage) {
            throw new CanNotHydrate(
                get_class($this) . ' cannot hydrate this data type'
            );
        }

        $apiMessages->add($apiMessageData);

        return $apiMessages;
    }
}
