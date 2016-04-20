<?php
/**
 * ITEA office copyright message placeholder.
 *
 * @category  Contact
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */

namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * @category    General
 */
class ContentType extends EntityRepository
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
        $queryBuilder->from("General\Entity\ContentType", 'c');


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
            case 'description':
                $queryBuilder->addOrderBy('c.description', $direction);
                break;
            case 'content-type':
                $queryBuilder->addOrderBy('c.contentType', $direction);
                break;
            case 'extension':
                $queryBuilder->addOrderBy('c.extension', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('c.id', $direction);
        }

        return $queryBuilder->getQuery();
    }

    /**
     * @return array
     */
    public function findContentTypeByImage()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('c.contentType', 'c.id');
        $queryBuilder->distinct();
        $queryBuilder->from("General\Entity\ContentType", 'c');
        $queryBuilder->join('c.contentImage', 'image');
        $queryBuilder->orderBy('c.contentType', 'ASC');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
