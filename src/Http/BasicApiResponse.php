<?php

namespace Reliv\RcmApiLib\Http;

use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BasicApiResponse implements ApiResponseInterface
{
    use BasicApiResponseTrait;

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @var string
     */
    protected $reasonPhrase = 'OK';

    /**
     * BasicApiResponse constructor.
     *
     * @param null  $data
     * @param array $apiMessages
     */
    public function __construct(
        $data = null,
        $apiMessages = []
    ) {
        $this->messages = new ApiMessages();
        $this->setData($data);
        $this->addApiMessages($apiMessages);
    }

    /**
     * @param ApiMessages $apiMessages
     *
     * @return BasicApiResponse|ApiResponseInterface
     */
    public function withApiMessages(ApiMessages $apiMessages)
    {
        $new = clone $this;
        $new->messages = $apiMessages;

        return $new;
    }

    /**
     * withStatus
     *
     * @param int    $statusCode
     * @param string $reasonPhrase
     *
     * @return $this
     */
    public function withStatus($statusCode, $reasonPhrase = '')
    {
        $this->statusCode = (int)$statusCode;
        $this->reasonPhrase = (string)$reasonPhrase;

        return $this;
    }
}
