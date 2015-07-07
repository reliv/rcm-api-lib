<?php


namespace Reliv\RcmApiLib\Hydrator;

use Reliv\RcmApiLib\Exception\ApiMessagesHydratorException;
use Reliv\RcmApiLib\Model\ApiMessage;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * Class ExceptionApiMessageHydrator
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
class ExceptionApiMessagesHydrator implements ApiMessagesHydratorInterface
{
    /**
     * hydrate
     *
     * @param mixed       $data
     * @param ApiMessages $apiMessages
     *
     * @return ApiMessages
     * @throws ApiMessagesHydratorException
     */
    public function hydrate($data, ApiMessages $apiMessages)
    {
        if (!$data instanceof \Exception) {
            throw new ApiMessagesHydratorException(
                get_class($this) . ' cannot hydrate this data type'
            );
        }

        $type = 'exception';
        $params = [];

        if (method_exists($data, 'getParms')) {
            // @todo this should be in its own hydrator
            $params = $data->getParms();
        }

        $code = $data->getCode();

        if (empty($code)) {
            $code = null;
        }

        $apiMessage = new ApiMessage(
            $type,
            $data->getMessage(),
            $this->getSourceString($data),
            $code,
            null,
            $params
        );

        $apiMessages->add($apiMessage);

        return $apiMessages;
    }

    /**
     * getSourceString
     *
     * @param $exception
     *
     * @return string
     */
    protected function getSourceString($exception)
    {
        $classname = get_class($exception);
        if ($pos = strrpos($classname, '\\')) {
            $classname = lcfirst(substr($classname, $pos + 1));
        }

        return $classname;
    }
}
