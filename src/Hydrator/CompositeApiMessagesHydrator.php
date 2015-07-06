<?php


namespace Reliv\RcmApiLib\Hydrator;

use Reliv\RcmApiLib\Exception\ApiMessagesHydratorException;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * Class CompositeApiMessageHydrator
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
class CompositeApiMessagesHydrator implements ApiMessagesHydratorInterface
{
    /**
     * @var array
     */
    protected $hydrators = [];

    /**
     * add
     *
     * @param ApiMessagesHydratorInterface $hydrator
     *
     * @return void
     */
    public function add(ApiMessagesHydratorInterface $hydrator)
    {
        $this->hydrators[] = $hydrator;
    }

    /**
     * hydrate
     *
     * @param mixed       $data
     * @param ApiMessages $apiMessages
     *
     * @return null|ApiMessages
     * @throws ApiMessagesHydratorException
     */
    public function hydrate($data, ApiMessages $apiMessages)
    {
        /** @var ApiMessagesHydratorInterface $hydrator */
        foreach ($this->hydrators as $hydrator) {
            try {
                $result = $hydrator->hydrate($data, $apiMessages);
            } catch (\Exception $exception) {
                // Cant do it :)
                $result = null;
            }

            if ($result !== null) {
                return $result;
            }
        }

        throw new ApiMessagesHydratorException(
            get_class($this) . ' cannot hydrate this data type: ' . gettype($data)
        );
    }
}
