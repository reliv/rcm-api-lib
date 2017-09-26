<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Reliv\RcmApiLib\Exception\CanNotHydrate;
use Reliv\RcmApiLib\Model\ApiMessages;
use Reliv\RcmApiLib\Model\StringApiMessage;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesString implements HydrateApiMessages
{
    /**
     * @var string
     */
    protected $defaultMessage;

    /**
     * @param string $defaultMessage
     */
    public function __construct(
        string $defaultMessage = 'An error occurred'
    ) {
        $this->defaultMessage = $defaultMessage;
    }

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
        if (!is_string($apiMessageData)) {
            throw new CanNotHydrate(
                get_class($this) . ' cannot hydrate this data type'
            );
        }

        if (empty($apiMessageData)) {
            $apiMessageData = $this->defaultMessage;
        }

        $apiMessage = new StringApiMessage(
            $apiMessageData
        );

        $apiMessages->add($apiMessage);

        return $apiMessages;
    }
}
