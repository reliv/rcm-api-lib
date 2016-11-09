<?php

namespace Reliv\RcmApiLib\Http;

use Reliv\RcmApiLib\Model\ApiMessages;
use Zend\Diactoros\Response;

/**
 * Class PsrApiResponse
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class PsrApiResponse extends Response implements ApiResponseInterface
{
    use BasicApiResponseTrait;

    /**
     * PsrApiResponse constructor.
     *
     * @param \Psr\Http\Message\StreamInterface|resource|string $body
     * @param int                                               $status
     * @param array                                             $headers
     * @param mixed                                             $data
     * @param array                                             $apiMessages
     */
    public function __construct(
        $body,
        $status,
        array $headers,
        $data = null,
        $apiMessages = []
    ) {
        $this->messages = new ApiMessages();
        $this->setData($data);
        $this->addApiMessages($apiMessages);

        parent::__construct($body, $status, $headers);
    }
}
