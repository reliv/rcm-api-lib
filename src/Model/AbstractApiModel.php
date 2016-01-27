<?php


namespace Reliv\RcmApiLib\Model;

/**
 * Class AbstractApiModel
 *
 * AbstractApiModel
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

abstract class AbstractApiModel implements ApiModelInterface
{
    /**
     * populate
     *
     * @param array $data
     * @param array $ignore List of properties to skip population for
     *
     * @return mixed
     */
    public function populate(
        array $data,
        array $ignore = []
    ) {
        $prefix = 'set';

        foreach ($data as $property => $value) {
            // Check for ignore keys
            if (in_array($property, $ignore)) {
                continue;
            }

            $method = $prefix . ucfirst($property);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * populateFromObject
     *
     * @param ApiPopulatableInterface $object Object of THIS type
     * @param array                   $ignore List of properties to skip population for
     *
     * @return mixed
     */
    public function populateFromObject(
        ApiPopulatableInterface $object,
        array $ignore = []
    ) {
        if ($object instanceof AbstractApiModel) {
            $this->populate($object->toArray());
        }
    }

    /**
     * toArray
     *
     * @param array $ignore List of properties to exclude from result
     *
     * @return array
     */
    public function toArray($ignore = [])
    {
        $prefix = 'get';
        $properties = get_object_vars($this);
        $data = [];

        foreach ($properties as $property => $value) {
            // Check for ignore keys
            if (in_array($property, $ignore)) {
                continue;
            }

            $method = $prefix . ucfirst($property);

            if (method_exists($this, $method)) {
                $data[$property] = $this->$method();
            }
        }

        return $data;
    }


    /**
     * modelArrayToArray
     *
     * @param array $modelArray
     * @param array $ignore
     *
     * @return array
     */
    protected function modelArrayToArray($modelArray, $ignore = [])
    {
        $array = [];

        /** @var ApiSerializableInterface $item */
        foreach ($modelArray as $item) {
            $array[] = $item->toArray($ignore);
        }

        return $array;
    }

    /**
     * jsonSerialize
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
