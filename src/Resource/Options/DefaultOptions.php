<?php

namespace Reliv\RcmApiLib\Resource\Options;

/**
 * Class DefaultOptions
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
Class DefaultOptions extends GenericOptions
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * DefaultControllerOptions constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $options = $config['Reliv\\RcmApiLib']['resource']['default'];
        parent::__construct($options);
    }
}
