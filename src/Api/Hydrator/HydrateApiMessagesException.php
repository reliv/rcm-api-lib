<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Reliv\RcmApiLib\Exception\CanNotHydrate;
use Reliv\RcmApiLib\Model\ApiMessages;
use Reliv\RcmApiLib\Model\ExceptionApiMessage;

/**
 * @author James Jervis - https://github.com/jerv13\
 */
class HydrateApiMessagesException implements HydrateApiMessages
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
        if (!$apiMessageData instanceof \Throwable) {
            throw new CanNotHydrate(
                get_class($this) . ' cannot hydrate this data type'
            );
        }

        $apiMessage = new ExceptionApiMessage(
            $apiMessageData
        );

        $apiMessages->add($apiMessage);

        return $apiMessages;
    }
}
