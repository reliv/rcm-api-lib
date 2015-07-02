<?php


namespace Reliv\RcmApiLib\Model;

/**
 * interface ApiPopulatableInterface
 *
 * ApiPopulatableInterface Interface
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */

interface ApiPopulatableInterface
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
    );

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
    );
}
