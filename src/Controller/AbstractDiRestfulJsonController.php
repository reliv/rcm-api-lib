<?php

namespace Reliv\RcmApiLib\Controller;

use Reliv\RcmApiLib\Http\ApiResponse;
use Reliv\RcmApiLib\Service\ResponseService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class AbstractDiRestfulJsonController extends AbstractRestfulJsonController
{
    /**
     * @override
     * @var ApiResponse
     */
    protected $responseService;

    /**
     * AbstractDiRestfulJsonController constructor.
     *
     * @param ResponseService $responseService
     */
    public function __construct(
        ResponseService $responseService
    ) {
        $this->responseService = $responseService;
    }

    /**
     * getResponseService
     *
     * @return ResponseService
     */
    protected function getResponseService()
    {
        return $this->responseService;
    }
}
