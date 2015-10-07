<?php
/**
 * DebraNova copyright message placeholder.
 *
 * @category  Contact
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */

namespace General\Repository;

use Affiliation\Service\AffiliationService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Event\Entity\Meeting\Meeting;
use Event\Entity\Registration;
use General\Entity;
use Program\Entity\Call\Call;
use Project\Entity\Evaluation;
use Project\Entity\Project;

/**
 * @category    Contact
 */
class Country extends EntityRepository
{
    /**
     * @param array ()
     *
     * @return QueryBuilder
     */
    public function findFiltered($filter)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_country');
        $queryBuilder->from('General\Entity\Country', 'general_entity_country');

        if (!is_null($filter)) {
            /**
             * Get the webInfo repository
             * @var  $webInfoRepository WebInfo
             */
            $queryBuilder = $this->applyWebInfoFilter($queryBuilder, $filter);
        }

        $direction = 'ASC';
        if (isset($filter['direction']) && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'])) {
            $direction = strtoupper($filter['direction']);
        }

        if (!array_key_exists('order', $filter)) {
            $filter['order'] = 'id';
        }

        switch ($filter['order']) {
            case 'id':
                $queryBuilder->addOrderBy('general_entity_country.id', $direction);
                break;
            case 'name':
                $queryBuilder->addOrderBy('general_entity_country.country', $direction);
                break;
            case 'iso3':
                $queryBuilder->addOrderBy('general_entity_country.iso3', $direction);
                break;
            case 'cd':
                $queryBuilder->addOrderBy('general_entity_country.cd', $direction);
                break;
            case 'numcode':
                $queryBuilder->addOrderBy('general_entity_country.numcode', $direction);
                break;
            default:
                $queryBuilder->addOrderBy('general_entity_country.country', $direction);

        }

        return $queryBuilder;
    }

    /**
     * SubSelect builder which limits the results of webInfos to only the active (Approved and FPP).
     *
     * @param QueryBuilder $queryBuilder
     * @param array $filter
     *
     * @return QueryBuilder
     */
    public function applyWebInfoFilter(QueryBuilder $queryBuilder, array $filter)
    {
        if (!empty($filter['search'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('general_entity_country.country', ':like'));
            $queryBuilder->setParameter('like', sprintf("%%%s%%", $filter['search']));
        }

        if (!empty($filter['eu'])) {
            $queryBuilder->innerJoin('general_entity_country.eu', 'eu');
        }

        if (!empty($filter['eureka'])) {
            $queryBuilder->innerJoin('general_entity_country.eureka', 'eureka');
        }

        if (!empty($filter['itac'])) {
            $queryBuilder->innerJoin('general_entity_country.itac', 'itac');
        }

        return $queryBuilder;
    }

    /**
     * This function returns an array with three elements.
     *
     * 'country' which contains the country object
     * 'partners' which contains the amount of partners
     * 'projects' which contains the amount of projects
     *
     *
     * project_id IN (
     * SELECT project_id
     * FROM project_version
     * WHERE type_id = 2 AND approved = 1)) AND
     * (project.project_id NOT IN (
     * SELECT project_id
     * FROM project_version
     * WHERE type_id = 4)
     *
     * @return array
     */
    public function findActive()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('a affiliation');
        $queryBuilder->addSelect(
            '(SELECT
                            COUNT(DISTINCT aff.organisation)
                            FROM Affiliation\Entity\Affiliation aff
                            JOIN aff.organisation org
                            JOIN aff.project pro
                            WHERE org.country = c AND aff.dateEnd IS NULL
                            AND pro IN (
                                SELECT proj1 FROM Project\Entity\Version\Version version1 JOIN version1.project proj1 JOIN version1.versionType type1 WHERE type1.id = 2 AND version1.approved = 1
                            ) AND pro NOT IN (
                                 SELECT proj2 FROM Project\Entity\Version\Version version2 JOIN version2.project proj2 JOIN version2.versionType type2 WHERE type2.id = 4
                            )
                            ) partners'
        );
        $queryBuilder->addSelect(
            '(SELECT
                            COUNT(DISTINCT aff2.project)
                            FROM Affiliation\Entity\Affiliation aff2
                            JOIN aff2.organisation org2
                            JOIN aff2.project pro2
                            WHERE org2.country = c AND aff2.dateEnd IS NULL
                            AND pro2 IN (
                                SELECT proj3 FROM Project\Entity\Version\Version version3 JOIN version3.project proj3 JOIN version3.versionType type3 WHERE type3.id = 2 AND version3.approved = 1
                            ) AND pro2 NOT IN (
                                SELECT proj4 FROM Project\Entity\Version\Version version4 JOIN version4.project proj4 JOIN version4.versionType type4 WHERE type4.id = 4
                            )
                            ) projects'
        );
        $queryBuilder->from('Affiliation\Entity\Affiliation', 'a');
        $queryBuilder->join('a.organisation', 'o');
        $queryBuilder->join('a.project', 'p');
        $queryBuilder->join('o.country', 'c');
        //Remove the 0 country (unknown)
        $queryBuilder->where('c.id <> 0');
        $queryBuilder->addGroupBy('c.id');
        $queryBuilder->addOrderBy('c.country');
        /**
         * @var $projectRepository \Project\Repository\Project
         */
        $projectRepository = $this->getEntityManager()->getRepository('Project\Entity\Project');
        $queryBuilder = $projectRepository->onlyActiveProject($queryBuilder);

        //only the active countries
        return $queryBuilder->getQuery()->useQueryCache(true)->useResultCache(true)->getResult();
    }

    /**
     * @param Call $call
     * @param int $which
     *
     * @throws \InvalidArgumentException
     *
     * @return Entity\Country[]
     */
    public function findCountryByCall(Call $call, $which)
    {
        $queryBuilder = $this->getQueryBuilderForCountryByWhich($which);

        $queryBuilder->andWhere('p.call = ?10');
        $queryBuilder->setParameter(10, $call);
        $queryBuilder->addOrderBy('c.iso3', 'ASC');

        return $queryBuilder->getQuery()->useQueryCache(true)->getResult();
    }

    /**
     * @param Project $project
     * @param int $which
     *
     * @throws \InvalidArgumentException
     *
     * @return Entity\Country[]
     */
    public function findCountryByProject(Project $project, $which)
    {
        $queryBuilder = $this->getQueryBuilderForCountryByWhich($which);
        $queryBuilder->andWhere('a.project = ?1');
        $queryBuilder->setParameter(1, $project);

        $queryBuilder->addOrderBy('c.country', 'ASC');

        return $queryBuilder->getQuery()->useQueryCache(true)->getResult();
    }

    /**
     * This function returns the country based on an IN query to avoid the unwanted hydration of the result.
     *
     * @param Project $project
     *
     * @throws \InvalidArgumentException
     *
     * @return null|Entity\Country[]
     */
    public function findCountryOfProjectContact(Project $project)
    {
        $findQueryBuilder = $this->_em->createQueryBuilder();
        $findQueryBuilder->select('c');
        $findQueryBuilder->from('Project\Entity\Project', 'p');
        $findQueryBuilder->join('p.contact', 'contact');
        $findQueryBuilder->join('contact.contactOrganisation', 'co');
        $findQueryBuilder->join('co.organisation', 'o');
        $findQueryBuilder->join('o.country', 'c');
        $findQueryBuilder->andWhere('p = ?1');

        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('country');
        $queryBuilder->from('General\Entity\Country', 'country');
        $queryBuilder->andWhere(
            $queryBuilder->expr()->in('country', $findQueryBuilder->getDQL())
        );

        $queryBuilder->setParameter(1, $project);

        return $queryBuilder->getQuery()->useResultCache(true)->getOneOrNullResult();
    }

    /**
     * Produces a default query to get a country and the required joins.
     *
     * @param $which
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderForCountryByWhich($which)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('c');
        $queryBuilder->from('General\Entity\Country', 'c');
        $queryBuilder->join('c.organisation', 'o');
        $queryBuilder->join('o.affiliation', 'a');
        $queryBuilder->join('a.project', 'p');
        //Remove the 0 country (unknown)
        $queryBuilder->where('c.id <> 0');
        $queryBuilder->addGroupBy('c.id');
        switch ($which) {
            case AffiliationService::WHICH_ALL:
                break;
            case AffiliationService::WHICH_ONLY_ACTIVE:
                $queryBuilder->andWhere($queryBuilder->expr()->isNull('a.dateEnd'));
                break;
            case AffiliationService::WHICH_ONLY_INACTIVE:
                $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('a.dateEnd'));
                break;
            default:
                throw new \InvalidArgumentException(sprintf("Incorrect value (%s) for which", $which));
        }

        return $queryBuilder;
    }

    /**
     * @param Call $call
     * @param Evaluation\Type $type
     *
     * @return Entity\Country[]
     */
    public function findCountryByEvaluationTypeAndCall(Evaluation\Type $type, Call $call = null)
    {
        $queryBuilder = $this->getQueryBuilderForCountryByWhich(AffiliationService::WHICH_ALL);
        $queryBuilder->join('p.evaluation', 'e');
        $queryBuilder->addOrderBy('c.country');
        /**
         * @var $projectRepository \Project\Repository\Project
         */
        $projectRepository = $this->getEntityManager()->getRepository('Project\Entity\Project');
        $queryBuilder = $projectRepository->onlyActiveProject($queryBuilder);
        $queryBuilder->andWhere('p.call = ?10');
        $queryBuilder->setParameter(10, $call);
        $queryBuilder->andWhere('e.type = ?11');
        $queryBuilder->setParameter(11, $type);

        return $queryBuilder->getQuery()->useQueryCache(true)->getResult();
    }

    /**
     * Find all countries active in the ITAC
     * This function returns an array with three elements.
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
        $queryBuilder->addSelect(
            '(SELECT
                            COUNT(DISTINCT aff.organisation)
                            FROM Affiliation\Entity\Affiliation aff
                            JOIN aff.organisation org
                            JOIN aff.project pro
                            WHERE org.country = c AND aff.dateEnd IS NULL
                            AND pro IN (
                                SELECT proj1 FROM Project\Entity\Version\Version version1 JOIN version1.project proj1 JOIN version1.versionType type1 WHERE type1.id = 2 AND version1.approved = 1
                            ) AND pro NOT IN (
                                 SELECT proj2 FROM Project\Entity\Version\Version version2 JOIN version2.project proj2 JOIN version2.versionType type2 WHERE type2.id = 4
                            )
                            ) partners'
        );
        $queryBuilder->addSelect(
            '(SELECT
                            COUNT(DISTINCT aff2.project)
                            FROM Affiliation\Entity\Affiliation aff2
                            JOIN aff2.organisation org2
                            JOIN aff2.project pro2
                            WHERE org2.country = c AND aff2.dateEnd IS NULL
                            AND pro2 IN (
                                SELECT proj3 FROM Project\Entity\Version\Version version3 JOIN version3.project proj3 JOIN version3.versionType type3 WHERE type3.id = 2 AND version3.approved = 1
                            ) AND pro2 NOT IN (
                                SELECT proj4 FROM Project\Entity\Version\Version version4 JOIN version4.project proj4 JOIN version4.versionType type4 WHERE type4.id = 4
                            )
                            ) projects'
        );
        $queryBuilder->innerJoin('c.itac', 'itac');
        //Remove the 0 country (unknown)
        $queryBuilder->where('c.id <> 0');

        return $queryBuilder->getQuery()->useQueryCache(true)->getResult();
    }

    /**
     * @param Meeting $meeting
     *
     * @return array
     */
    public function findCountriesByMeeting(Meeting $meeting)
    {
        $query = $this->_em->createQueryBuilder();
        $query->distinct('country.id');
        $query->select('country.id');
        $query->addSelect('country.country');
        $query->from('Event\Entity\Registration', 'r');
        $query->where('r.meeting = ?1');
        $query->setParameter(1, $meeting->getId());
        $query->andWhere($query->expr()->isNull('r.dateEnd'));
        $query->andWhere('r.hideInList = ?2');
        $query->andWhere('r.overbooked = ?3');
        $query->setParameter(2, Registration::NOT_HIDE_IN_LIST);
        $query->setParameter(3, Registration::NOT_OVERBOOKED);
        $query->join('r.contact', 'c');
        $query->join('c.contactOrganisation', 'co');
        $query->join('co.organisation', 'o');
        $query->join('o.country', 'country');

        return $query->getQuery()->useQueryCache(true)->getResult();
    }
}
