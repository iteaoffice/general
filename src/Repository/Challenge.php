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
class Challenge extends EntityRepository
{
    /**
     * @param array $filter
     *
     * @return Query
     */
    public function findFiltered(array $filter)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_challenge');
        $queryBuilder->from(Entity\Challenge::class, 'general_entity_challenge');


        $direction = 'DESC';
        if (isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
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
            default:
                $queryBuilder->addOrderBy('general_entity_challenge.challenge', 'ASC');
        }

        return $queryBuilder->getQuery();
    }
}
