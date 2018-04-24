<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Contact
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use General\Entity;

/**
 * Class ContentType
 * @package General\Repository
 */
class ContentType extends EntityRepository
{
    /**
     * @param array $filter
     *
     * @return Query
     */
    public function findFiltered(array $filter): Query
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_content_type');
        $queryBuilder->from(Entity\ContentType::class, 'general_entity_content_type');


        $direction = 'DESC';
        if (isset($filter['direction'])
            && \in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_content_type.id', $direction);
                break;
            case 'description':
                $queryBuilder->addOrderBy('general_entity_content_type.description', $direction);
                break;
            case 'content-type':
                $queryBuilder->addOrderBy('general_entity_content_type.contentType', $direction);
                break;
            case 'extension':
                $queryBuilder->addOrderBy('general_entity_content_type.extension', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_content_type.description', 'ASC');
        }

        return $queryBuilder;
    }

    /**
     * @return array
     */
    public function findContentTypeByImage(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_content_type.contentType', 'general_entity_content_type.id');
        $queryBuilder->distinct();
        $queryBuilder->from(Entity\ContentType::class, 'general_entity_content_type');
        $queryBuilder->join('general_entity_content_type.contentImage', 'image');
        $queryBuilder->orderBy('general_entity_content_type.contentType', 'ASC');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
