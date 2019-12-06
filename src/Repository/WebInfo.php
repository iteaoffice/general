<?php
/**
 * Jield webdev copyright message placeholder
 *
 * @category    General
 * @package     Repository
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   @copyright Copyright (c) 2004-2017 ITEA Office (http://itea3.org)
 * @license     http://jield.net/license.txt proprietary
 * @link        http://jield.net
 */

declare(strict_types=1);

namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use General\Entity;
use function in_array;

/**
 * Class WebInfo
 *
 * @package General\Repository
 */
class WebInfo extends EntityRepository
{
    public function findFiltered(array $filter = null): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_web_info');
        $queryBuilder->from(Entity\WebInfo::class, 'general_entity_web_info');
        $queryBuilder->join('general_entity_web_info.sender', 'mailing_entity_sender');

        if (null !== $filter) {
            /**
             * Get the webInfo repository
             *
             * @var  $webInfoRepository WebInfo
             */
            $queryBuilder = $this->applyWebInfoFilter($queryBuilder, $filter);
        }

        $direction = 'ASC';
        if (isset($filter['direction']) && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)) {
            $direction = strtoupper($filter['direction']);
        }

        if (!array_key_exists('order', $filter)) {
            $filter['order'] = 'info';
        }

        switch ($filter['order']) {
            case 'info':
                $queryBuilder->addOrderBy('general_entity_web_info.info', $direction);
                break;
            case 'subject':
                $queryBuilder->addOrderBy('general_entity_web_info.subject', $direction);
                break;
            case 'sender':
                $queryBuilder->addOrderBy('mailing_entity_sender.sender', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_web_info.info', $direction);
        }

        return $queryBuilder;
    }

    public function applyWebInfoFilter(QueryBuilder $queryBuilder, array $filter): QueryBuilder
    {
        if (!empty($filter['search'])) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('general_entity_web_info.info', ':like'),
                    $queryBuilder->expr()->like('general_entity_web_info.subject', ':like'),
                    $queryBuilder->expr()->like('general_entity_web_info.content', ':like'),
                    $queryBuilder->expr()->like('mailing_entity_sender.sender', ':like')
                )
            );


            $queryBuilder->setParameter('like', sprintf('%%%s%%', $filter['search']));
        }

        return $queryBuilder;
    }
}
