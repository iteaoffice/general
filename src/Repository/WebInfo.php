<?php
/**
 * Jield webdev copyright message placeholder
 *
 * @category    General
 * @package     Repository
 * @author      Johan van der Heide <info@jield.nl>
 * @copyright   2004-2015 Jield webdev
 * @license     http://jield.net/license.txt proprietary
 * @link        http://jield.net
 */
namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Doctrine2 Repository for the WebInfo
 *
 * @category    SoloDB
 * @package     Admin
 * @subpackage  Repository
 */
class WebInfo extends EntityRepository
{
    /**
     * @param array ()
     *
     * @return QueryBuilder
     */
    public function findFiltered($filter)
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
    public function applyWebInfoFilter(QueryBuilder $queryBuilder, array $filter)
    {
        if (! empty($filter['search'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('general_entity_web_info.info', ':like'));
            $queryBuilder->setParameter('like', sprintf("%%%s%%", $filter['search']));
        }

        return $queryBuilder;
    }
}
