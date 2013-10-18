<?php
/**
 * DebraNova copyright message placeholder
 *
 * @category    Contact
 * @package     Repository
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */
namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use Project\Entity\VersionType;
use Project\Entity\Version;

use General\Entity;

/**
 * @category    Contact
 * @package     Repository
 */
class Country extends EntityRepository
{
    /**
     * @return Entity\Country[];
     */
    public function findActive()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('c');
        $queryBuilder->from('General\Entity\Country', 'c');

        $queryBuilder->join('c.organisation', 'o');
        $queryBuilder->join('o.affiliation', 'a');

        //Remove the 0 country (unknown)
        $queryBuilder->where('c.id <> 0');

        $subSelect = $this->_em->createQueryBuilder();
        $subSelect->select('p.id');
        $subSelect->from('Project\Entity\Version', 'pv');
        $subSelect->join('pv.project', 'p');
        $subSelect->where('pv.approved = ?1');
        $subSelect->andWhere('pv.versionType = ?2');

        $subSelect2 = $this->_em->createQueryBuilder();
        $subSelect2->select('p2.id');
        $subSelect2->from('Project\Entity\Version', 'pv2');
        $subSelect2->join('pv2.project', 'p2');
        $subSelect2->andWhere('pv2.versionType = ?3');

        $queryBuilder->andWhere($queryBuilder->expr()->in('a.project', $subSelect->getDQL()));
        $queryBuilder->andWhere($queryBuilder->expr()->notIn('a.project', $subSelect2->getDQL()));
        $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('a.dateEnd'));

        $queryBuilder->setParameter(1, Version::STATUS_APPROVED);
        $queryBuilder->setParameter(2, VersionType::TYPE_FPP);
        $queryBuilder->setParameter(3, VersionType::TYPE_SR);

        return $queryBuilder->getQuery()->getResult();
    }
}
