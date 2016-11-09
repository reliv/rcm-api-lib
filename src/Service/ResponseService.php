<?php

namespace Reliv\RcmApiLib\Service;

use Interop\Container\ContainerInterface;
use RcmI18n\Service\ParameterizeTranslator;
use Reliv\RcmApiLib\Http\ApiResponseInterface;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * Class ResponseService
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ResponseService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ParameterizeTranslator
     */
    protected $parameterizeTranslator;

    /**
     * ResponseService constructor.
     *
     * @param ContainerInterface     $container
     * @param ParameterizeTranslator $parameterizeTranslator
     */
    public function __construct(
        $container,
        ParameterizeTranslator $parameterizeTranslator
    ) {
        $this->container = $container;
        $this->parameterizeTranslator = $parameterizeTranslator;
    }

    /**
     * getHydrator
     *
     * @return \Reliv\RcmApiLib\Hydrator\ApiMessagesHydratorInterface
     */
    protected function getHydrator()
    {
        return $this->container->get('Reliv\RcmApiLib\Hydrator');
    }

    /**
     * getParameterizeTranslator
     *
     * @return ParameterizeTranslator
     */
    protected function getParameterizeTranslator()
    {
        return $this->parameterizeTranslator;
    }

    /**
     * translateMessage
     *
     * @param        $message
     * @param array  $params
     * @param string $textDomain
     * @param null   $locale
     *
     * @return mixed
     */
    public function translateMessage(
        $message,
        $params = [],
        $textDomain = 'default',
        $locale = null
    ) {
        return $this->getParameterizeTranslator()->translate(
            $message,
            $params,
            $textDomain,
            $locale
        );
    }

    /**
     * translateApiResponseMessages
     *
     * @return void
     */
    public function translateApiResponseMessages(
        ApiResponseInterface $response
    ) {
        $apiMessages = $response->getApiMessages();

        /** @var ApiMessage $apiMessage */
        foreach ($apiMessages as $apiMessage) {
            $apiMessage->setValue(
                $this->translateMessage(
                    $apiMessage->getValue(),
                    $apiMessage->getParams()
                )
            );
        }
    }

    /**
     * getMethodNotAllowed
     *
     * @param ApiResponseInterface $response
     *
     * @return ApiResponseInterface
     */
    public function getMethodNotAllowed(
        ApiResponseInterface $response
    ) {
        $apiMessage = new ApiMessage(
            'Http',
            'Method Not Allowed',
            'Method_Not_Allowed',
            '405',
            true
        );

        return $this->getApiResponse(
            $response,
            null,
            405,
            $apiMessage
        );
    }

    /**
     * getApiResponse
     *
     * @param ApiResponseInterface $response
     * @param                      $data
     * @param int                  $statusCode
     * @param null                 $apiMessagesData
     *
     * @return ApiResponseInterface
     */
    public function getApiResponse(
        ApiResponseInterface $response,
        $data,
        $statusCode = 200,
        $apiMessagesData = null
    ) {
        $response->setData($data);

        if (!empty($apiMessagesData)) {
            $this->addApiMessage(
                $response,
                $apiMessagesData
            );
        }

        $this->translateApiResponseMessages(
            $response
        );

        return $response->withStatus($statusCode);
    }

    /**
     * setApiMessage
     *
     * @param ApiResponseInterface $response
     * @param ApiMessage           $apiMessage
     *
     * @return void
     */
    public function setApiMessage(
        ApiResponseInterface $response,
        ApiMessage $apiMessage
    ) {
        $response->addApiMessage($apiMessage);
    }

    /**
     * addApiMessage
     *
     * @param ApiResponseInterface $response
     * @param mixed                $apiMessagesData
     *
     * @return void
     */
    public function addApiMessage(
        ApiResponseInterface $response,
        $apiMessagesData
    ) {
        $hydrator = $this->getHydrator();

        $apiMessages = $response->getApiMessages();

        $hydrator->hydrate($apiMessagesData, $apiMessages);
    }

    /**
     * setApiMessages
     *
     * @param ApiResponseInterface $response
     * @param ApiMessages          $apiMessages
     *
     * @return void
     */
    public function setApiMessages(
        ApiResponseInterface $response,
        ApiMessages $apiMessages
    ) {
        $response->setApiMessages($apiMessages);
    }

    /**
     * addApiMessages
     *
     * @param ApiResponseInterface $response
     * @param array|\ArrayIterator $apiMessagesData
     *
     * @return void
     */
    public function addApiMessages(
        ApiResponseInterface $response,
        $apiMessagesData
    ) {
        foreach ($apiMessagesData as $apiMessage) {
            $this->addApiMessage($response, $apiMessage);
        }
    }

    /**
     * getApiMessages
     *
     * @param ApiResponseInterface $response
     *
     * @return ApiMessages
     */
    public function getApiMessages(
        ApiResponseInterface $response
    ) {
        return $response->getApiMessages();
    }

    /**
     * hasApiMessages
     *
     * @param ApiResponseInterface $response
     *
     * @return bool
     */
    public function hasApiMessages(
        ApiResponseInterface $response
    ) {
        $apiMessages = $this->getApiMessages(
            $response
        );

        return $apiMessages->has();
    }
}
