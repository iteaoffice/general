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
 * Class Title
 *
 * @package General\Repository
 */
class Title extends EntityRepository
{
    public function findFiltered(array $filter): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_title');
        $queryBuilder->from(Entity\Title::class, 'general_entity_title');

        $direction = 'DESC';
        if (
            isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_title.id', $direction);
                break;
            case 'name':
                $queryBuilder->addOrderBy('general_entity_title.name', $direction);
                break;
            case 'attention':
                $queryBuilder->addOrderBy('general_entity_title.attention', $direction);
                break;
            case 'salutation':
                $queryBuilder->addOrderBy('general_entity_title.salutation', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_title.id', $direction);
        }

        return $queryBuilder;
    }
}
