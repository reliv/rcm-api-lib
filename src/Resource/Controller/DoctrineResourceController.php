<?php

namespace Reliv\RcmApiLib\Resource\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Reliv\RcmApiLib\Model\ApiPopulatableInterface;
use Reliv\RcmApiLib\Resource\Exception\EntityDoesNotHaveSingleIdentifierField;
use Reliv\RcmApiLib\Resource\Exception\EntityDoesNotImplementApiPopulatableInterface;
use Reliv\RcmApiLib\Resource\Exception\EntityMissingIdSetter;
use Reliv\RcmApiLib\Resource\Exception\RequestBodyWasNotParsed;
use Reliv\RcmApiLib\Resource\Exception\RequestBodyWasNotParsedException;
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
     * @return string
     */
    protected function getEntityName(Request $request)
    {
        return $this->getOption($request, 'entity', null);
    }

    /**
     * Returns the entity by the ID that is in the request or
     * null if the ID is not in the DB.
     *
     * @param Request $request
     * @return null|object
     */
    protected function getEntityByRequestId(Request $request)
    {
        $id = $this->getRouteParam($request, 'id');

        return $this->getRepository($request)->find($id);
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

    /**
     * Adds the given entity to the DB.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws EntityDoesNotHaveSingleIdentifierField
     * @throws EntityDoesNotImplementApiPopulatableInterface
     * @throws EntityMissingIdSetter
     */
    public function create(Request $request, Response $response)
    {
        $entityName = $this->getEntityName($request);
        $entity = new $entityName();

        $this->populateEntity($entity, $request);

        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);

        return $this->formatResponse($request, $response, $entity);
    }

    /**
     * Updates the entity with the given ID with the request body.
     * If the entity is not in the DB, it will be created.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws EntityDoesNotHaveSingleIdentifierField
     * @throws EntityDoesNotImplementApiPopulatableInterface
     * @throws EntityMissingIdSetter
     */
    public function upsert(Request $request, Response $response)
    {
        $entity = $this->getEntityByRequestId($request);

        if (!is_object($entity)) {
            $entityName = $this->getEntityName($request);
            $entity = new $entityName();
            $this->setEntityId($entity, $this->getRouteParam($request, 'id'));
            $this->entityManager->persist($entity);
        }

        $this->populateEntity($entity, $request);
        $this->entityManager->flush($entity);

        return $this->updateProperties($request, $response);
    }

    /**
     * Returns 200, true if the entity with the given ID exists in DB.
     * Returns 404, false if the entity does not exist in DB.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function exists(Request $request, Response $response)
    {
        if (!is_object($this->getEntityByRequestId($request))) {
            return $this->formatResponse($request, $response, true);
        }

        return $this->formatResponse($request, $response, false)->withStatus(404);

    }

    /**
     * Finds the the entity with the given ID and returns it.
     * If the entity is not in the DB, return 404.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function findById(Request $request, Response $response)
    {
        $entity = $this->getEntityByRequestId($request);

        if (!is_object($entity)) {
            return $response->withStatus(404);
        }

        return $this->formatResponse($request, $response, $entity);
    }

    public function find(Request $request, Response $response)
    {

    }

    public function findOne(Request $request, Response $response)
    {

    }

    /**
     * Deletes the entity with the give ID from the DB.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function deleteById(Request $request, Response $response)
    {
        $entity = $this->getEntityByRequestId($request);

        if (!is_object($entity)) {
            return $response->withStatus(404);
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush($entity);

        return $this->formatResponse($request, $response, $entity);
    }

    /**
     * Returns the count of the entities given.
     * @TODO take ?filter=something into account if it is in the request
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function count(Request $request, Response $response)
    {
        $entityName = $this->getEntityName($request);
        $count = $this->entityManager
            ->createQuery('SELECT COUNT(e) FROM :entityName e')
            ->setParameter('entityName', $entityName)
            ->getSingleScalarResult();

        if (!class_exists($entityName)) {
            return $response->withStatus(404);
        }

        return $this->formatResponse($request, $response, $count);
    }

    /**
     * Updates the entity with the given ID with the request body.
     * IF the entity is not in the DB, return 404.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws EntityDoesNotImplementApiPopulatableInterface
     */
    public function updateProperties(Request $request, Response $response)
    {
        $entity = $this->getEntityByRequestId($request);

        if (!is_object($entity)) {
            return $response->withStatus(404);
        }

        $this->populateEntity($entity, $request);
        $this->entityManager->flush($entity);

        return $this->formatResponse($request, $response, $entity);
    }

    /**
     * Asks doctrine for the entity's ID field name and calls the
     * appropriate setter to set the given entity's ID.
     *
     * @param Object $entity
     * @param string $id
     * @throws EntityDoesNotHaveSingleIdentifierField
     * @throws EntityMissingIdSetter
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    protected function setEntityId($entity, $id)
    {
        $entityName = get_class($entity);
        $meta = $this->entityManager->getClassMetadata($entityName);
        $idName = $meta->getSingleIdentifierFieldName();
        if (empty($idName)) {
            throw new EntityDoesNotHaveSingleIdentifierField($entityName);
        }
        $setter = 'set' . ucfirst($idName);
        if (!method_exists($entity, $setter)) {
            throw new EntityMissingIdSetter(
                'The entity ' . $entityName . ' is missing function ' . $setter . '()'
            );
        }
        $entity->$setter($id);
    }

    /**
     * Populates the given entity from the given request's body.
     * If an earlier middleware parses the body into the "body"
     * request attribute, that attribute will be used rather than
     *
     * @param $entity
     * @param Request $request
     * @throws EntityDoesNotImplementApiPopulatableInterface
     * @throws RequestBodyWasNotParsedException
     */
    protected function populateEntity($entity, Request $request)
    {
        if (!$entity instanceof ApiPopulatableInterface) {
            throw new EntityDoesNotImplementApiPopulatableInterface();
        }

        $body = $request->getAttribute('body', new RequestBodyWasNotParsedException());
        if ($body instanceof RequestBodyWasNotParsedException) {
            $body = $request->getBody()->getContents();
        }

        $entity->populate($body);
    }
}
