<?php

namespace Reliv\RcmApiLib\Hydrator;

use Reliv\RcmApiLib\Exception\ApiMessagesHydratorException;
use Reliv\RcmApiLib\Model\ApiMessages;

/**
 * @author James Jervis - https://github.com/jerv13
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
