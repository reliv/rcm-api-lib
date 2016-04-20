<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Resource\Options\Options;

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
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * getEntityName
     *
     * @param Request $request
     *
     * @return Options
     */
    protected function getEntityName(Request $request)
    {
        return $this->getControllerOption($request, 'entity', null);
    }

    /**
     * getRepository
     *
     * @param Request $request
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository(Request $request)
    {
        $entityName = $this->getEntityName($request);
        
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
        $id = $this->getRouteParam($request, 'id');
        
        $this->getRepository($request)->find($id);
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
