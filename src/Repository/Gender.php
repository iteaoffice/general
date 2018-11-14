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
use Doctrine\ORM\QueryBuilder;
use General\Entity;

/**
 * Class Gender
 *
 * @package General\Repository
 */
class Gender extends EntityRepository
{
    /**
     * @param array $filter
     *
     * @return QueryBuilder
     */
    public function findFiltered(array $filter): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_gender');
        $queryBuilder->from(Entity\Gender::class, 'general_entity_gender');


        $direction = 'DESC';
        if (isset($filter['direction'])
            && \in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_gender.id', $direction);
                break;
            case 'name':
                $queryBuilder->addOrderBy('general_entity_gender.name', $direction);
                break;
            case 'attention':
                $queryBuilder->addOrderBy('general_entity_gender.attention', $direction);
                break;
            case 'salutation':
                $queryBuilder->addOrderBy('general_entity_gender.salutation', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_gender.id', $direction);
        }

        return $queryBuilder;
    }
}
