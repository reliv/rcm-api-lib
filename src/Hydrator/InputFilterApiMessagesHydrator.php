<?php


namespace Reliv\RcmApiLib\Hydrator;

use Reliv\RcmApiLib\Exception\ApiMessagesHydratorException;
use Reliv\RcmApiLib\InputFilter\MessageParamInputFilter;
use Reliv\RcmApiLib\InputFilter\MessageParamInterface;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class InputFilterApiMessageHydrator
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\Hydrator\Http
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class InputFilterApiMessagesHydrator implements ApiMessagesHydratorInterface
{

    /**
     * @var null|string
     */
    protected $primaryMessage = null;

    /**
     * @var string type for fields
     */
    protected $typeName = 'inputFilter';

    /**
     * @param $primaryMessage
     */
    public function __construct($primaryMessage)
    {
        $this->primaryMessage = $primaryMessage;
    }

    /**
     * hydrate
     *
     * @param             $data
     * @param ApiMessages $apiMessages
     *
     * @return ApiMessages
     * @throws ApiMessagesHydratorException
     */
    public function hydrate($data, ApiMessages $apiMessages)
    {
        if (!$data instanceof InputFilterInterface) {
            throw new ApiMessagesHydratorException(
                get_class($this) . ' cannot hydrate this data type'
            );
        }

        $params = [];

        if ($data instanceof MessageParamInterface) {
            $params = $data->getMessageParams();
        }

        $primaryApiMessage = new ApiMessage(
            $this->typeName,
            $this->primaryMessage,
            'overallFieldsMessage',
            'error',
            true,
            $params
        );

        $apiMessages->add($primaryApiMessage);

        $inputs = $data->getInvalidInput();

        /**
         * @var string                           $fieldName
         * @var \Zend\InputFilter\InputInterface $input
         */
        foreach ($inputs as $fieldName => $input) {
            $validatorChain = $input->getValidatorChain();
            $validators = $validatorChain->getValidators();

            $this->buildValidatorMessages($apiMessages, $fieldName, $validators);
        }

        return $apiMessages;
    }

    /**
     * buildValidatorMessages
     *
     * @param ApiMessages $apiMessages
     * @param             $source
     * @param             $validators
     *
     * @return ApiMessages
     */
    protected function buildValidatorMessages(
        ApiMessages $apiMessages,
        $source,
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

            $this->buildApiMessages($apiMessages, $source, $inputMessages, $params);
        }

        return $apiMessages;
    }

    /**
     * buildApiMessages
     *
     * @param ApiMessages $apiMessages
     * @param string      $source
     * @param array       $inputMessages
     * @param array       $params
     *
     * @return ApiMessages
     */
    protected function buildApiMessages(
        ApiMessages $apiMessages,
        $source,
        $inputMessages,
        $params = []
    ) {
        foreach ($inputMessages as $fkey => $message) {
            $apiMessage = new ApiMessage(
                $this->typeName,
                $message,
                $source,
                $fkey,
                null,
                $params
            );

            $apiMessages->add($apiMessage);
        }

        return $apiMessages;
    }
}
