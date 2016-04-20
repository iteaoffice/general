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
class Vat extends EntityRepository
{
    /**
     * @param array $filter
     *
     * @return Query
     */
    public function findFiltered(array $filter)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('v');
        $queryBuilder->from("General\Entity\Vat", 'v');
        $queryBuilder->join("v.country", 'c');


        $direction = 'DESC';
        if (isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'])
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('v.id', $direction);
                break;
            case 'code':
                $queryBuilder->addOrderBy('v.code', $direction);
                break;
            case 'percentage':
                $queryBuilder->addOrderBy('v.percentage', $direction);
                break;
            case 'date-start':
                $queryBuilder->addOrderBy('v.dateStart', $direction);
                break;
            case 'country':
                $queryBuilder->addOrderBy('v.country', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('v.id', $direction);
        }

        return $queryBuilder->getQuery();
    }
}
