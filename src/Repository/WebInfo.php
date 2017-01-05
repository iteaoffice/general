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
namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class WebInfo
 *
 * @package General\Repository
 */
class WebInfo extends EntityRepository
{
    /**
     * @param $filter
     *
     * @return QueryBuilder
     */
    public function findFiltered(array $filter = null): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_web_info');
        $queryBuilder->from('General\Entity\WebInfo', 'general_entity_web_info');

        if (! is_null($filter)) {
            /**
             * Get the webInfo repository
             *
             * @var  $webInfoRepository WebInfo
             */
            $queryBuilder = $this->applyWebInfoFilter($queryBuilder, $filter);
        }

        $direction = 'ASC';
        if (isset($filter['direction']) && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'])) {
            $direction = strtoupper($filter['direction']);
        }

        if (! array_key_exists('order', $filter)) {
            $filter['order'] = 'info';
        }

        switch ($filter['order']) {
            case 'info':
                $queryBuilder->addOrderBy('general_entity_web_info.info', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_web_info.info', $direction);
        }

        return $queryBuilder;
    }

    /**
     * SubSelect builder which limits the results of webInfos to only the active (Approved and FPP).
     *
     * @param QueryBuilder $queryBuilder
     * @param array        $filter
     *
     * @return QueryBuilder
     */
    public function applyWebInfoFilter(QueryBuilder $queryBuilder, array $filter): QueryBuilder
    {
        if (! empty($filter['search'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('general_entity_web_info.info', ':like'));
            $queryBuilder->setParameter('like', sprintf("%%%s%%", $filter['search']));
        }

        return $queryBuilder;
    }
}
