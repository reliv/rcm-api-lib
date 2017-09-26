<?php

namespace Reliv\RcmApiLib\Api\Hydrator;

use Reliv\RcmApiLib\Exception\CanNotHydrate;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HydrateApiMessagesDefault implements HydrateApiMessages
{
    /**
     * @var string
     */
    protected $defaultType;

    /**
     * @var string
     */
    protected $defaultValue;

    /**
     * @var string
     */
    protected $defaultSource;

    /**
     * @var string
     */
    protected $defaultCode;

    /**
     * @param string $defaultType
     * @param string $defaultValue
     * @param string $defaultSource
     * @param string $defaultCode
     */
    public function __construct(
        $defaultType = 'generic',
        $defaultValue = 'An error occurred',
        $defaultSource = 'unknown',
        $defaultCode = 'fail'
    ) {
        $this->defaultType = $defaultType;
        $this->defaultValue = $defaultValue;
        $this->defaultSource = $defaultSource;
        $this->defaultCode = $defaultCode;
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
        $apiMessage = new ApiMessage(
            $this->defaultType,
            $this->defaultValue,
            $this->defaultSource,
            $this->defaultCode,
            null,
            [
                'messageData' => json_encode($apiMessageData, 0, 5)
            ]
        );

        $apiMessages->add($apiMessage);

        return $apiMessages;
    }
}
