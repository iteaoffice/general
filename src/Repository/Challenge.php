<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use General\Entity;

use function in_array;

/**
 * Class Challenge
 * @package General\Repository
 */
class Challenge extends EntityRepository
{
    public function findFiltered(array $filter): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_challenge');
        $queryBuilder->from(Entity\Challenge::class, 'general_entity_challenge');


        if (null !== $filter) {
            $queryBuilder = $this->applyFilter($queryBuilder, $filter);
        }

        $direction = Criteria::DESC;
        if (
            isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), [Criteria::ASC, Criteria::DESC], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_challenge.id', $direction);
                break;
            case 'challenge':
                $queryBuilder->addOrderBy('general_entity_challenge.challenge', $direction);
                break;
            case 'sequence':
                $queryBuilder->addOrderBy('general_entity_challenge.sequence', $direction);
                break;
            case 'type':
                $queryBuilder->addOrderBy('general_entity_challenge_type.type', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_challenge.challenge', 'ASC');
        }

        return $queryBuilder;
    }

    public function applyFilter(
        QueryBuilder $queryBuilder,
        array $filter
    ): QueryBuilder {
        if (! empty($filter['search'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('general_entity_challenge.challenge', ':like'));
            $queryBuilder->setParameter('like', sprintf('%%%s%%', $filter['search']));
        }

        if (isset($filter['type'])) {
            $queryBuilder->leftJoin('general_entity_challenge.type', 'general_entity_challenge_type');
            $queryBuilder->andWhere($queryBuilder->expr()->in('general_entity_challenge_type.id', $filter['type']));
        }

        if (isset($filter['call'])) {
            $queryBuilder->leftJoin('general_entity_challenge.call', 'program_entity_call_call');
            $queryBuilder->andWhere($queryBuilder->expr()->in('program_entity_call_call.id', $filter['call']));
        }


        return $queryBuilder;
    }

    public function findNotActiveForCallsChallenges(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_challenge');
        $queryBuilder->from(Entity\Challenge::class, 'general_entity_challenge');
        $queryBuilder->join('general_entity_challenge.type', 'general_entity_challenge_type');
        $queryBuilder->where('general_entity_challenge_type.activeForCalls = :active');
        $queryBuilder->addOrderBy('general_entity_challenge.sequence', Criteria::ASC);
        $queryBuilder->setParameter('active', Entity\Challenge\Type::NOT_ACTIVE_FOR_CALLS);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findActiveForCallsChallenges(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_challenge');
        $queryBuilder->from(Entity\Challenge::class, 'general_entity_challenge');
        $queryBuilder->join('general_entity_challenge.type', 'general_entity_challenge_type');
        $queryBuilder->where('general_entity_challenge_type.activeForCalls = :active');
        $queryBuilder->addOrderBy('general_entity_challenge.sequence', Criteria::ASC);
        $queryBuilder->setParameter('active', Entity\Challenge\Type::ACTIVE_FOR_CALLS);

        return $queryBuilder->getQuery()->getResult();
    }
}
