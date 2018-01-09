<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Contact
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use General\Entity;

/**
 * @category    Contact
 */
class Currency extends EntityRepository
{
    /**
     * @param array ()
     *
     * @return QueryBuilder
     */
    public function findFiltered($filter): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_currency');
        $queryBuilder->from(Entity\Currency::class, 'general_entity_currency');

        if (null !== $filter) {
            $queryBuilder = $this->applyFilter($queryBuilder, $filter);
        }

        $direction = 'ASC';
        if (isset($filter['direction'])
            && \in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        if (!array_key_exists('order', $filter)) {
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
            $queryBuilder->andWhere($queryBuilder->expr()->like('general_entity_currency.name', ':like'));
            $queryBuilder->setParameter('like', sprintf("%%%s%%", $filter['search']));
        }

        return $queryBuilder;
    }
}
