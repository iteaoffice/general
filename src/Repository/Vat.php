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
 * Class Vat
 *
 * @package General\Repository
 */
class Vat extends EntityRepository
{
    public function findFiltered(array $filter): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_vat');
        $queryBuilder->from(Entity\Vat::class, 'general_entity_vat');
        $queryBuilder->join('general_entity_vat.country', 'general_entity_country');


        $direction = 'DESC';
        if (
            isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_vat.id', $direction);
                break;
            case 'code':
                $queryBuilder->addOrderBy('general_entity_vat.code', $direction);
                break;
            case 'percentage':
                $queryBuilder->addOrderBy('general_entity_vat.percentage', $direction);
                break;
            case 'date-start':
                $queryBuilder->addOrderBy('general_entity_vat.dateStart', $direction);
                break;
            case 'country':
                $queryBuilder->addOrderBy('general_entity_country.country', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_vat.id', $direction);
        }

        return $queryBuilder;
    }
}
