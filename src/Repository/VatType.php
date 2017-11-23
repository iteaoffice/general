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
use General\Entity;

/**
 * @category    General
 */
class VatType extends EntityRepository
{
    /**
     * @param array $filter
     *
     * @return Query
     */
    public function findFiltered(array $filter): Query
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_vattype');
        $queryBuilder->from(Entity\VatType::class, 'general_entity_vattype');
        $queryBuilder->join("general_entity_vattype.vat", 'general_entity_vat');


        $direction = 'DESC';
        if (isset($filter['direction'])
            && \in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_vattype.id', $direction);
                break;
            case 'type':
                $queryBuilder->addOrderBy('general_entity_vattype.type', $direction);
                break;
            case 'description':
                $queryBuilder->addOrderBy('general_entity_vattype.description', $direction);
                break;
            case 'vat':
                $queryBuilder->addOrderBy('general_entity_vat.code', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_vattype.type', $direction);
        }

        return $queryBuilder->getQuery();
    }
}
