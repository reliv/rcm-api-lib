<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Reliv\RcmApiLib\Exception\CanNotHydrate;
use Reliv\RcmApiLib\Model\ApiMessages;
use Reliv\RcmApiLib\Model\ArrayApiMessage;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesArray implements HydrateApiMessages
{
    /**
     * hydrate
     *
     * @param mixed $apiMessageData
     * @param ApiMessages $apiMessages
     *
     * @return ApiMessages
     * @throws CanNotHydrate
     */
    public function __invoke($apiMessageData, ApiMessages $apiMessages)
    {
        if (is_array($apiMessageData) && array_key_exists('value', $apiMessageData)
            && array_key_exists(
                'type',
                $apiMessageData
            )
        ) {
            $apiMessage = new ArrayApiMessage(
                $apiMessageData
            );

            $apiMessages->add($apiMessage);

            return $apiMessages;
        }

        throw new CanNotHydrate(
            get_class($this) . ' cannot hydrate this data type'
        );
    }
}
