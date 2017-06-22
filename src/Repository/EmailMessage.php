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

declare(strict_types=1);

namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use General\Entity;

/**
 * @category    General
 */
class EmailMessage extends EntityRepository
{
    /**
     * @param array $filter
     *
     * @return Query
     */
    public function findFiltered(array $filter): Query
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_email_message');
        $queryBuilder->from(Entity\EmailMessage::class, 'general_entity_email_message');
        $queryBuilder->leftJoin('general_entity_email_message.contact', 'contact_entity_contact');

        if (!empty($filter['search'])) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('contact_entity_contact.firstName', ':like'),
                    $queryBuilder->expr()->like('contact_entity_contact.middleName', ':like'),
                    $queryBuilder->expr()->like('contact_entity_contact.lastName', ':like'),
                    $queryBuilder->expr()->like('general_entity_email_message.emailAddress', ':like')
                )
            );
            $queryBuilder->setParameter('like', sprintf("%%%s%%", $filter['search']));
        }

        if (!empty($filter['latestEvent'])) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()
                    ->in('general_entity_email_message.latestEvent', $filter['latestEvent'])
            );
        }


        $direction = 'DESC';
        if (isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = strtoupper($filter['direction']);
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_email_message.id', $direction);
                break;
            case 'subject':
                $queryBuilder->addOrderBy('general_entity_email_message.subject', $direction);
                break;
            case 'latest_event':
                $queryBuilder->addOrderBy('general_entity_email_message.latestEvent', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_email_message.id', 'DESC');
        }

        return $queryBuilder->getQuery();
    }

    /**
     * @return array
     */
    public function findPossibleLatestEvents(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_email_message.latestEvent');
        $queryBuilder->distinct(true);
        $queryBuilder->from(Entity\EmailMessage::class, 'general_entity_email_message');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
