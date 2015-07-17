<?php

namespace Reliv\RcmApiLib\Model;

use Reliv\RcmApiLib\InputFilter\MessageParamInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class InputFilterApiMessages
 *
 * InputFilterApiMessages
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmApiLib\Model
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class InputFilterApiMessages extends ApiMessages
{

    /**
     * @var string
     */
    protected $primaryMessage = 'An Error Occurred';

    /**
     * @var string
     */
    protected $primarySource = 'overallFieldsMessage';

    /**
     * @var string
     */
    protected $primaryCode = 'error';

    /**
     * @param InputFilterInterface $inputFilter
     * @param string               $primaryMessage
     * @param array                $params
     */
    public function __construct(
        InputFilterInterface $inputFilter,
        $primaryMessage = 'An Error Occurred',
        $params = []
    ) {
        $this->primaryMessage = $primaryMessage;
        $this->build($inputFilter, $params);
    }

    /**
     * build
     *
     * @param InputFilterInterface $inputFilter
     * @param array                $params
     *
     * @return void
     */
    public function build(InputFilterInterface $inputFilter, $params = [])
    {

        if ($inputFilter instanceof MessageParamInterface) {
            $params = array_merge($inputFilter->getMessageParams(), $params);
        }

        $primaryApiMessage = new ValidatorMessageApiMessage(
            $this->primaryMessage,
            $this->primarySource,
            $this->primaryCode,
            $params,
            true
        );

        $this->add($primaryApiMessage);

        $inputs = $inputFilter->getInvalidInput();

        /**
         * @var string                           $fieldName
         * @var \Zend\InputFilter\InputInterface $input
         */
        foreach ($inputs as $fieldName => $input) {
            $validatorChain = $input->getValidatorChain();
            $validators = $validatorChain->getValidators();

            $this->buildValidatorMessages($fieldName, $validators);
        }
    }

    /**
     * buildValidatorMessages
     *
     * @param string $fieldName
     * @param        $validators
     *
     * @return void
     */
    protected function buildValidatorMessages(
        $fieldName,
        $validators
    ) {
        foreach ($validators as $fkey => $validatorData) {
            /** @var \Zend\Validator\ValidatorInterface $validator */
            $validator = $validatorData['instance'];
            $params = [];

            if ($validator instanceof MessageParamInterface) {
                $params = $validator->getMessageParams();
            }

            $inputMessages = $validator->getMessages();

            $this->buildApiMessages($fieldName, $inputMessages, $params);
        }
    }

    /**
     * buildApiMessages
     *
     * @param string $fieldName
     * @param array  $inputMessages
     * @param array  $params
     *
     * @return ApiMessages
     */
    protected function buildApiMessages(
        $fieldName,
        $inputMessages,
        $params = []
    ) {
        foreach ($inputMessages as $errorKey => $message) {
            $apiMessage = new ValidatorMessageApiMessage(
                $message,
                $fieldName,
                $errorKey,
                $params,
                null
            );

            $this->add($apiMessage);
        }
    }
}
