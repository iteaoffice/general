<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    General
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Repository\Challenge;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use General\Entity\Challenge;

/**
 * Class Type
 *
 * @package General\Repository\Challenge
 */
class Type extends EntityRepository
{
    /**
     * @param array $filter
     *
     * @return QueryBuilder
     */
    public function findFiltered(array $filter): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_challenge_type');
        $queryBuilder->from(Challenge\Type::class, 'general_entity_challenge_type');


        $direction = 'DESC';
        if (isset($filter['direction'])
            && \in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        /**
         * Filter on the name
         */
        if (array_key_exists('search', $filter)) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('general_entity_challenge_type.type', ':like'));
            $queryBuilder->setParameter('like', sprintf("%%%s%%", $filter['search']));
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_challenge_type.id', $direction);
                break;
            case 'type':
                $queryBuilder->addOrderBy('general_entity_challenge_type.type', $direction);
                break;
            case 'sequence':
                $queryBuilder->addOrderBy('general_entity_challenge_type.sequence', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_challenge_type.id', $direction);
        }

        return $queryBuilder;
    }
}
