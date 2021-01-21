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
 * Class Currency
 *
 * @package General\Repository
 */
class Currency extends EntityRepository
{
    public function findFiltered(array $filter): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_currency');
        $queryBuilder->from(Entity\Currency::class, 'general_entity_currency');

        if (null !== $filter) {
            $queryBuilder = $this->applyFilter($queryBuilder, $filter);
        }

        $direction = 'ASC';
        if (
            isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        if (! array_key_exists('order', $filter)) {
            $filter['order'] = 'name';
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_currency.id', $direction);
                break;
            case 'name':
                $queryBuilder->addOrderBy('general_entity_currency.name', $direction);
                break;
            case 'iso3':
                $queryBuilder->addOrderBy('general_entity_currency.iso4217', $direction);
                break;
            case 'symbol':
                $queryBuilder->addOrderBy('general_entity_currency.symbol', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_currency.name', $direction);
        }

        return $queryBuilder;
    }

    public function applyFilter(
        QueryBuilder $queryBuilder,
        array $filter
    ): QueryBuilder {
        if (! empty($filter['search'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('general_entity_currency.name', ':like'));
            $queryBuilder->setParameter('like', sprintf('%%%s%%', $filter['search']));
        }

        return $queryBuilder;
    }
}
