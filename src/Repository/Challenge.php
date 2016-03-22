<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * PHP Version 5
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   2004-2016 ITEA Office
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

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
        $queryBuilder->select('c');
        $queryBuilder->from("General\Entity\Challenge", 'c');


        $direction = 'DESC';
        if (isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'])
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('c.id', $direction);
                break;
            case 'challenge':
                $queryBuilder->addOrderBy('c.challenge', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('c.id', $direction);

        }

        return $queryBuilder->getQuery();
    }
}
