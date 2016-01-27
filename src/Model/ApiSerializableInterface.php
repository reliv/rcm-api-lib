<?php


namespace Reliv\RcmApiLib\Model;

/**
 * interface ApiSerializableInterface
 *
 * ApiSerializableInterface Interface
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

interface ApiSerializableInterface extends \JsonSerializable
{
    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return mixed
     */
    public function toArray($ignore = []);
}
