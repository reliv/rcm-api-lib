<?php

namespace Reliv\RcmApiLib\Resource\Options;

/**
 * interface Options
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
interface RuntimeOptions
{
    /**
     * buildRuntimeOptions
     * - Defaults with runtime options merged in
     *
     * @param array $runTimeOptions
     *
     * @return Options
     */
    public function buildRuntimeOptions(array $runTimeOptions = null);
}
