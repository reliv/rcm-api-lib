<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Options\DefaultControllerOptions;

/**
 * Class DoctrineResourceController
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class DoctrineResourceController extends AbstractResourceController
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * DoctrineResourceController constructor.
     *
     * @param DefaultControllerOptions $defaultControllerOptions
     * @param EntityManager            $entityManager
     */
    public function __construct(
        DefaultControllerOptions $defaultControllerOptions,
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
        $defaultOptions = $defaultControllerOptions->getOptions(
            'Reliv\RcmApiLib\Resource\Controller\DoctrineResourceController'
        );
        parent::__construct($defaultOptions);
    }

    /**
     * getRepository
     *
     * @param $entityName
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository($entityName)
    {
        return $this->entityManager->getRepository($entityName);
    }

    public function create(Request $request, Response $response)
    {

    }

    public function upsert(Request $request, Response $response)
    {

    }

    public function exists(Request $request, Response $response)
    {

    }

    public function findById(Request $request, Response $response)
    {
        $id = $this->getUrlParam('id');
    }

    public function find(Request $request, Response $response)
    {

    }

    public function findOne(Request $request, Response $response)
    {

    }

    public function deleteById(Request $request, Response $response)
    {

    }

    public function count(Request $request, Response $response)
    {

    }

    public function updateProperties(Request $request, Response $response)
    {

    }
}
