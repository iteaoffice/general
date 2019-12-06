<?php
/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Repository;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use General\Entity;
use function in_array;

/**
 * Class Log
 *
 * @package General\Repository
 */
class Log extends EntityRepository
{
    public function findFiltered(array $filter): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_log');
        $queryBuilder->from(Entity\Log::class, 'general_entity_log');

        if (!empty($filter['search'])) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('general_entity_log.event', ':like'),
                    $queryBuilder->expr()->like('general_entity_log.url', ':like'),
                    $queryBuilder->expr()->like('general_entity_log.file', ':like')
                )
            );
            $queryBuilder->setParameter('like', sprintf('%%%s%%', $filter['search']));
        }

        $direction = 'DESC';
        if (isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_log.id', $direction);
                break;
            case 'date':
                $queryBuilder->addOrderBy('general_entity_log.date', $direction);
                break;
            case 'event':
                $queryBuilder->addOrderBy('general_entity_log.event', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_log.id', 'DESC');
        }

        return $queryBuilder;
    }

    public function truncateLog(): void
    {
        $truncateQuery = 'TRUNCATE TABLE log';
        $this->getEntityManager()->getConnection()->executeQuery($truncateQuery);
    }
}
