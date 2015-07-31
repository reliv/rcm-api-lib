<?php

namespace Reliv\RcmApiLib\Model;

use Reliv\RcmApiLib\InputFilter\MessageParamInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputInterface;

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
    protected $primaryType = 'inputFilter';

    /**
     * @var string
     */
    protected $primaryMessage = 'An Error Occurred';

    /**
     * @var string
     */
    protected $primarySource = 'validation';

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

        $primaryApiMessage = new ApiMessage(
            $this->primaryType,
            $this->primaryMessage,
            $this->primarySource,
            $this->primaryCode,
            true,
            $params
        );

        $this->add($primaryApiMessage);

        $this->parseInputs($inputFilter);
    }

    /**
     * parseInputs
     *
     * @param        $input
     * @param string $name
     *
     * @return void
     */
    protected function parseInputs($input, $name = '')
    {
        if ($input instanceof InputFilterInterface) {
            $inputs = $input->getInvalidInput();

            foreach ($inputs as $key => $subinput) {
                $fieldName = $this->getParseName($name, $key, $subinput);
                $this->parseInputs($subinput, $fieldName);
            }

            return;
        }

        $validatorChain = $input->getValidatorChain();
        $validators = $validatorChain->getValidators();

        $this->buildValidatorMessages($name, $validators);
    }

    /**
     * getParseName
     *
     * @param $name
     * @param $key
     * @param $subinput
     *
     * @return string
     */
    protected function getParseName($name, $key, $subinput)
    {
        $fieldName = $key;
        if (method_exists($subinput, 'getName')) {
            $fieldName = $subinput->getName();
        }
        if (!empty($name)) {
            $fieldName = $name . '-' . $fieldName;
        }
        return $fieldName;
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
