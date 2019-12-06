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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use General\Entity;
use function in_array;

/**
 * Class VatType
 * @package General\Repository
 */
class VatType extends EntityRepository
{
    public function findFiltered(array $filter): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_vattype');
        $queryBuilder->from(Entity\VatType::class, 'general_entity_vattype');
        $queryBuilder->join('general_entity_vattype.vat', 'general_entity_vat');


        $direction = 'DESC';
        if (isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_vattype.id', $direction);
                break;
            case 'type':
                $queryBuilder->addOrderBy('general_entity_vattype.type', $direction);
                break;
            case 'description':
                $queryBuilder->addOrderBy('general_entity_vattype.description', $direction);
                break;
            case 'vat':
                $queryBuilder->addOrderBy('general_entity_vat.code', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_vattype.type', $direction);
        }

        return $queryBuilder;
    }
}
