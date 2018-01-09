<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use General\Entity;

/**
 * @category    General
 */
class Challenge extends EntityRepository
{
    /**
     * @param array $filter
     *
     * @return Query
     */
    public function findFiltered(array $filter): Query
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_challenge');
        $queryBuilder->from(Entity\Challenge::class, 'general_entity_challenge');
        $queryBuilder->leftJoin('general_entity_challenge.type', 'general_entity_challenge_type');
        $queryBuilder->leftJoin('general_entity_challenge.call', 'program_entity_call_call');

        if (null !== $filter) {
            $queryBuilder = $this->applyFilter($queryBuilder, $filter);
        }

        $direction = 'DESC';
        if (isset($filter['direction'])
            && \in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
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

        return $queryBuilder->getQuery();
    }

    /**
     * SubSelect builder which limits the results of webInfos to only the active (Approved and FPP).
     *
     * @param QueryBuilder $queryBuilder
     * @param array $filter
     *
     * @return QueryBuilder
     */
    public function applyFilter(
        QueryBuilder $queryBuilder,
        array $filter
    ): QueryBuilder {
        if (!empty($filter['search'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('general_entity_challenge.challenge', ':like'));
            $queryBuilder->setParameter('like', sprintf("%%%s%%", $filter['search']));
        }

        if (isset($filter['type'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('general_entity_challenge_type.id', $filter['type']));
        }

        if (isset($filter['call'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('program_entity_call_call.id', $filter['call']));
        }


        return $queryBuilder;
    }
}
