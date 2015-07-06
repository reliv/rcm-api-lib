<?php

namespace Reliv\RcmApiLib\InputFilter;

/**
 * Class MessageParamInputFilter
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
interface MessageParamInterface
{
    /**
     * getMessageParams
     *
     * @return array
     */
    public function getMessageParams();

    /**
     * setMessageParams
     *
     * @param $messageParams ['key' => 'value']
     *
     * @return void
     */
    public function setMessageParams(array $messageParams);
}
