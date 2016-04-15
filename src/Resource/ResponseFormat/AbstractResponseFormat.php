<?php

namespace Reliv\RcmApiLib\Resource\ResponseFormat;

use Reliv\RcmApiLib\Resource\Options\Options;
use Reliv\RcmApiLib\Resource\Options\RuntimeOptions;

/**
 * Class AbstractResponseFormat
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
abstract class AbstractResponseFormat implements ResponseFormat
{
    /**
     * @var Options
     */
    protected $defaultOptions;

    /**
     * AbstractResponseFormat constructor.
     *
     * @param Options $defaultOptions
     */
    public function __construct(Options $defaultOptions)
    {
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * buildRuntimeOptions
     *
     * @param array|null $runTimeOptions
     *
     * @return Options
     */
    public function buildRuntimeOptions(array $runTimeOptions = null)
    {
        $defaultOptions = clone($this->defaultOptions);

        $defaultOptions->setFromArray($runTimeOptions);

        return $defaultOptions;
    }
}
