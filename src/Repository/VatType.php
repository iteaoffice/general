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
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

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
    public function findFiltered(array $filter)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('vt');
        $queryBuilder->from("General\Entity\VatType", 'vt');
        $queryBuilder->join("vt.vat", 'v');


        $direction = 'DESC';
        if (isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'])
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('vt.id', $direction);
                break;
            case 'type':
                $queryBuilder->addOrderBy('vt.type', $direction);
                break;
            case 'description':
                $queryBuilder->addOrderBy('vt.description', $direction);
                break;
            case 'vat':
                $queryBuilder->addOrderBy('v.vat', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('vt.type', $direction);
        }

        return $queryBuilder->getQuery();
    }
}
