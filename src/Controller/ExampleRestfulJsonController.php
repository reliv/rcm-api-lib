<?php

namespace Reliv\RcmApiLib\Controller;

use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ArrayApiMessage;
use Reliv\RcmApiLib\Model\ExceptionApiMessage;
use Reliv\RcmApiLib\Model\HttpStatusCodeApiMessage;
use Reliv\RcmApiLib\Model\InputFilterApiMessages;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\StringLength;

/**
 * Class ExampleRestfulJsonController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Controller
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ExampleRestfulJsonController extends AbstractRestfulJsonController
{

    public function get($id)
    {
        $exampleData = [
            'id' => $id,
            'email' => 'example',
            'password' => 'example'
        ];

        $exampleMethod = 'getApiResponse' . ucfirst($id) . 'Example';

        if (method_exists($this, $exampleMethod)) {
            return $this->$exampleMethod($exampleData);
        }

        return $this->getApiResponseAllExample($exampleData);
    }

    /**
     * Example of the standard (testable) way to build ApiMessages
     * - More control over the details
     * - More code
     * - Testable
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    protected function getApiResponseAllExample($exampleData)
    {
        /* ApiMessage */
        $apiMessage = new ApiMessage('exampleApiMessage');
        $apiMessage->setSource('exampleSource');
        $apiMessage->setCode('exampleCode');
        $apiMessage->setValue('Example message: {param}');
        $apiMessage->setParams(['param' => 'example param value']);
        $this->addApiMessage(
            $apiMessage
        );

        /* ArrayApiMessage */
        $arrayApiMessage = new ArrayApiMessage(
            [
                'type' => 'exampleArrayApiMessage',
                'source' => 'exampleSource',
                'code' => 'exampleCode',
                'value' => 'Example message: {param}',
                'params' => ['param' => 'example param value']
            ]
        );
        $this->addApiMessage(
            $arrayApiMessage
        );

        /* ExceptionApiMessage */
        $exception = new \Exception(
            'Example Exception Message',
            22
        );
        $exceptionApiMessage = new ExceptionApiMessage($exception);
        $this->addApiMessage(
            $exceptionApiMessage
        );

        /* HttpStatusCodeApiMessage */
        $httpStatusCodeApiMessage = new HttpStatusCodeApiMessage(404);
        $this->addApiMessage(
            $httpStatusCodeApiMessage
        );

        /* InputFilterApiMessages NOTE: this is a collection of messages */
        $email = new Input('email');
        $email->getValidatorChain()
            ->attach(new EmailAddress());

        $password = new Input('password');
        $password->getValidatorChain()
            ->attach(new StringLength(['min' => 8]));

        $inputFilter = new InputFilter();
        $inputFilter->add($email)
            ->add($password)
            ->setData($exampleData);

        // Do validation
        $inputFilter->isValid();

        $inputFilterApiMessages = new InputFilterApiMessages(
            $inputFilter
        );

        $this->addApiMessages(
            $inputFilterApiMessages
        );

        /* Return the response */
        return $this->getApiResponse(
            $exampleData
        );
    }

    /**
     * Example of simple API message using getApiResponse() hydrator
     *
     * @param $exampleData
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    protected function getApiResponseApiExample($exampleData)
    {
        $apiMessage = new ApiMessage('exampleApiMessage');
        $apiMessage->setSource('exampleSource');
        $apiMessage->setCode('exampleCode');
        $apiMessage->setValue('Example message: {param}');
        $apiMessage->setParams(['param' => 'example param value']);

        /* Return the response */
        return $this->getApiResponse(
            $exampleData,
            400,
            $apiMessage
        );
    }

    /**
     * Example of simple array message using getApiResponse() hydrator
     *
     * @param $exampleData
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    protected function getApiResponseArrayExample($exampleData)
    {
        $arrayMessage = [
            'type' => 'exampleArrayApiMessage',
            'source' => 'exampleSource',
            'code' => 'exampleCode',
            'value' => 'Example message: {param}',
            'params' => ['param' => 'example param value']
        ];

        /* Return the response */
        return $this->getApiResponse(
            $exampleData,
            400,
            $arrayMessage
        );
    }

    /**
     * Example of simple exception message using getApiResponse() hydrator
     *
     * @param $exampleData
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    protected function getApiResponseExceptionExample($exampleData)
    {
        $exception = new \Exception(
            'Example Exception Message',
            22
        );

        /* Return the response */
        return $this->getApiResponse(
            $exampleData,
            400,
            $exception
        );
    }

    /**
     * Example of simple input filter message using getApiResponse() hydrator
     *
     * @param $exampleData
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    protected function getApiResponseInputFilterExample($exampleData)
    {
        $email = new Input('email');
        $email->getValidatorChain()
            ->attach(new EmailAddress());

        $password = new Input('password');
        $password->getValidatorChain()
            ->attach(new StringLength(['min' => 8]));

        $inputFilter = new InputFilter();
        $inputFilter->add($email)
            ->add($password)
            ->setData($exampleData);

        // Do validation
        $inputFilter->isValid();

        /* Return the response */
        return $this->getApiResponse(
            $exampleData,
            400,
            $inputFilter
        );
    }
}
