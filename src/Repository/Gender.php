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
class Gender extends EntityRepository
{
    /**
     * @param array $filter
     *
     * @return Query
     */
    public function findFiltered(array $filter)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('g');
        $queryBuilder->from("General\Entity\Gender", 'g');


        $direction = 'DESC';
        if (isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'])
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('g.id', $direction);
                break;
            case 'name':
                $queryBuilder->addOrderBy('g.name', $direction);
                break;
            case 'attention':
                $queryBuilder->addOrderBy('g.attention', $direction);
                break;
            case 'salutation':
                $queryBuilder->addOrderBy('g.salutation', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('g.id', $direction);
        }

        return $queryBuilder->getQuery();
    }
}
