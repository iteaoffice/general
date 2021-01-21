<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Service;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use General\Entity\AbstractEntity;

/**
 * Class AbstractService
 *
 * @package Project\Service
 */
abstract class AbstractService
{
    protected EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findFiltered(string $entity, array $filter): QueryBuilder
    {
        return $this->entityManager->getRepository($entity)->findFiltered(
            $filter,
            AbstractQuery::HYDRATE_SIMPLEOBJECT
        );
    }

    public function findAll(string $entity): array
    {
        return $this->entityManager->getRepository($entity)->findAll();
    }

    public function find(string $entity, int $id): ?AbstractEntity
    {
        return $this->entityManager->getRepository($entity)->find($id);
    }

    public function findByName(string $entity, string $column, string $name): ?AbstractEntity
    {
        return $this->entityManager->getRepository($entity)->findOneBy([$column => $name]);
    }

    public function save(AbstractEntity $entity): AbstractEntity
    {
        if (! $this->entityManager->contains($entity)) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
        return $entity;
    }

    public function delete(AbstractEntity $abstractEntity): void
    {
        $this->entityManager->remove($abstractEntity);
        $this->entityManager->flush();
    }

    public function refresh(AbstractEntity $abstractEntity): void
    {
        $this->entityManager->refresh($abstractEntity);
    }

    public function detach(AbstractEntity $abstractEntity): void
    {
        $this->entityManager->detach($abstractEntity);
    }
}
