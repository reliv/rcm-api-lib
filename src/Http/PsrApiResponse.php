<?php

namespace Reliv\RcmApiLib\Http;

use Reliv\RcmApiLib\Model\ApiMessages;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

/**
 * @todo      Should be immutable
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
     * @var int
     */
    protected $encodingOptions;

    /**
     * PsrApiResponse constructor.
     *
     * @param null  $data
     * @param array $apiMessages
     * @param int   $status
     * @param array $headers
     * @param int   $encodingOptions
     */
    public function __construct(
        $data = null,
        $apiMessages = [],
        $status = 200,
        array $headers = [],
        $encodingOptions = 0
    ) {
        $this->messages = new ApiMessages();
        $this->setData($data);
        $this->addApiMessages($apiMessages);
        $this->encodingOptions = $encodingOptions;

        $headers = $this->injectContentType('application/json', $headers);

        parent::__construct(
            $this->getBody(),
            $status,
            $headers
        );
    }

    /**
     * @param ApiMessages $apiMessages
     *
     * @return PsrApiResponse|ApiResponseInterface
     */
    public function withApiMessages(ApiMessages $apiMessages)
    {
        $new = clone $this;
        $new->messages = $apiMessages;

        return $new;
    }

    /**
     * To mimic \Zend\Diactoros\Response\JsonResponse
     *
     * @return mixed
     */
    public function getPayload()
    {
        return $this->data;
    }

    /**
     * To mimic \Zend\Diactoros\Response\JsonResponse
     *
     * @param $data
     *
     * @return PsrApiResponse
     */
    public function withPayload($data)
    {
        $new = clone $this;
        $new->setData($data);

        return $new;
    }

    /**
     * Inject the provided Content-Type, if none is already present.
     *
     * @param string $contentType
     * @param array  $headers
     *
     * @return array Headers with injected Content-Type
     */
    protected function injectContentType($contentType, array $headers)
    {
        $hasContentType = array_reduce(
            array_keys($headers),
            function ($carry, $item) {
                return $carry ?: (strtolower($item) === 'content-type');
            },
            false
        );

        if (!$hasContentType) {
            $headers['content-type'] = [$contentType];
        }

        return $headers;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getContent()
    {
        $data = $this->getData();
        $messages = $this->getApiMessages();

        if (is_resource($data)) {
            throw new \InvalidArgumentException('Cannot JSON encode resources');
        }

        // Clear json_last_error()
        json_encode(null);

        $content = [
            'data' => $data,
            'messages' => $messages,
        ];

        $json = json_encode($content, $this->encodingOptions);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Unable to encode data to JSON in %s: %s',
                    __CLASS__,
                    json_last_error_msg()
                )
            );
        }

        return $json;
    }

    /**
     * getBody
     *
     * @return Stream
     */
    public function getBody()
    {
        $body = new Stream('php://temp', 'wb+');
        $body->write($this->getContent());
        $body->rewind();

        return $body;
    }
}
