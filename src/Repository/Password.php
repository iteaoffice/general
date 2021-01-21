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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use General\Entity;

use function in_array;

/**
 * Class WebInfo
 *
 * @package General\Repository
 */
class Password extends EntityRepository
{
    public function findFiltered(array $filter = null): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_password');
        $queryBuilder->from(Entity\Password::class, 'general_entity_password');

        if (null !== $filter) {
            $queryBuilder = $this->applyWebInfoFilter($queryBuilder, $filter);
        }

        $direction = 'ASC';
        if (isset($filter['direction']) && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)) {
            $direction = strtoupper($filter['direction']);
        }

        if (! array_key_exists('order', $filter)) {
            $filter['order'] = 'info';
        }

        switch ($filter['order']) {
            case 'description':
                $queryBuilder->addOrderBy('general_entity_password.description', $direction);
                break;
            case 'account':
                $queryBuilder->addOrderBy('general_entity_password.account', $direction);
                break;
            case 'username':
                $queryBuilder->addOrderBy('general_entity_password.username', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_password.description', $direction);
        }

        return $queryBuilder;
    }

    public function applyWebInfoFilter(QueryBuilder $queryBuilder, array $filter): QueryBuilder
    {
        if (! empty($filter['search'])) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('general_entity_password.description', ':like'),
                    $queryBuilder->expr()->like('general_entity_password.account', ':like'),
                    $queryBuilder->expr()->like('general_entity_password.username', ':like')
                )
            );


            $queryBuilder->setParameter('like', sprintf('%%%s%%', $filter['search']));
        }

        return $queryBuilder;
    }
}
