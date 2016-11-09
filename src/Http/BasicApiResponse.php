<?php

namespace Reliv\RcmApiLib\Http;

use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * Class BasicApiResponse
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
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
