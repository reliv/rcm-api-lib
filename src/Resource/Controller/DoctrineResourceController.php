<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Stratigility\Route;

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
     * @param array         $config
     * @param EntityManager $entityManager
     */
    public function __construct($config, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($config);
    }

    protected function getRepository($repositoryName)
    {
        return $this->entityManager->getRepository($repositoryName);
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
