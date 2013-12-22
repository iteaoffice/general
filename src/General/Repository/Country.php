<?php
/**
 * DebraNova copyright message placeholder
 *
 * @category    Contact
 * @package     Repository
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use General\Entity;

/**
 * @category    Contact
 * @package     Repository
 */
class Country extends EntityRepository
{
    /**
     * This function returns an array with three elements
     *
     * 'country' which contains the country object
     * 'partners' which contains the amount of partners
     * 'projects' which contains the amount of projects
     *
     * @return array
     */
    public function findActive()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('a affiliation');

        $queryBuilder->addSelect('COUNT(DISTINCT a.organisation) partners');
        $queryBuilder->addSelect('COUNT(DISTINCT a.project) projects');

        $queryBuilder->from('Affiliation\Entity\Affiliation', 'a');

        $queryBuilder->join('a.organisation', 'o');
        $queryBuilder->join('o.country', 'c');

        //Remove the 0 country (unknown)
        $queryBuilder->where('c.id <> 0');

        $queryBuilder->addGroupBy('c.id');
        $queryBuilder->addOrderBy('c.country');

        //Limit to only the active projects
        $projectRepository = $this->getEntityManager()->getRepository('Project\Entity\Project');
        $queryBuilder      = $projectRepository->onlyActiveProject($queryBuilder);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Find all countries active in the ITAC
     * This function returns an array with three elements
     *
     * 'country' which contains the country object
     * 'partners' which contains the amount of partners
     * 'projects' which contains the amount of projects
     *
     * @return array
     */
    public function findItac()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('c country');
        $queryBuilder->from('General\Entity\Country', 'c');

        $queryBuilder->addSelect('(SELECT
                COUNT(DISTINCT aff.organisation)
                FROM Affiliation\Entity\Affiliation aff
                JOIN aff.organisation org WHERE org.country = c AND aff.dateEnd IS NULL) partners');
        $queryBuilder->addSelect('(SELECT
                COUNT(DISTINCT aff2.project)
                FROM Affiliation\Entity\Affiliation aff2
                JOIN aff2.organisation org2 WHERE org2.country = c AND aff2.dateEnd IS NULL) projects');

        $queryBuilder->innerJoin('c.itac', 'itac');

        $queryBuilder->join('c.organisation', 'o');
        $queryBuilder->join('o.affiliation', 'a');

        //Remove the 0 country (unknown)
        $queryBuilder->where('c.id <> 0');

        return $queryBuilder->getQuery()->getResult();
    }
}
