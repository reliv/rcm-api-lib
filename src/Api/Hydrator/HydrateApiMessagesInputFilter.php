<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Reliv\RcmApiLib\Exception\CanNotHydrate;
use Reliv\RcmApiLib\Model\ApiMessages;
use Reliv\RcmApiLib\Model\InputFilterApiMessages;
use Zend\InputFilter\InputFilterInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesInputFilter implements HydrateApiMessages
{
    /**
     * @var null|string
     */
    protected $primaryMessage = null;

    /**
     * @param $primaryMessage
     */
    public function __construct($primaryMessage)
    {
        $this->primaryMessage = $primaryMessage;
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
        if (!$apiMessageData instanceof InputFilterInterface) {
            throw new CanNotHydrate(
                get_class($this) . ' cannot hydrate this data type'
            );
        }

        $inputFilterApiMessages = new InputFilterApiMessages(
            $apiMessageData,
            $this->primaryMessage
        );

        foreach ($inputFilterApiMessages as $apiMessage) {
            $apiMessages->add($apiMessage);
        }

        return $apiMessages;
    }
}
