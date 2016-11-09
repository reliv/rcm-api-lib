<?php

namespace Reliv\RcmApiLib\Http;

use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;
use Zend\Http\Headers;
use Zend\Http\Response as HttpResponse;

/**
 * Class ApiResponse
 *
 * JSON ApiResponse
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ApiResponse extends HttpResponse implements ApiResponseInterface
{
    use BasicApiResponseTrait;

    /**
     * @var mixed
     */
    protected $data = null;

    /**
     * @var ApiMessages
     */
    protected $messages;

    /**
     * ApiResponse constructor.
     *
     * @param null $apiMessages
     *
     * @throws \Exception
     */
    public function __construct($apiMessages = null)
    {
        /** @var Headers $headers */
        $headers = $this->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/json');

        $this->messages = $apiMessages;

        if ($apiMessages instanceof ApiMessages) {
            $this->setApiMessages($apiMessages);
        } else {
            $this->setApiMessages(new ApiMessages());
        }
    }

    /**
     * setDataOject
     *
     * @param \JsonSerializable $data
     *
     * @return void
     */
    public function setDataOject(\JsonSerializable $data)
    {
        $this->data = $data;
    }

    /**
     * setContent
     *
     * @param mixed $content
     *
     * @return void
     * @throws \Exception
     */
    public function setContent($content)
    {
        throw new \Exception(
            'Content cannot be set directly, use setData, or addMssages'
        );
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getContent()
    {
        $content = [
            'data' => $this->getData(),
            'messages' => $this->getApiMessages(),
        ];

        $json = json_encode($content);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(json_last_error_msg());
        }

        return $json;
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
        parent::setStatusCode($statusCode);
        parent::setReasonPhrase($reasonPhrase);

        return $this;
    }
}
