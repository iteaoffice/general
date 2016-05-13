<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  Contact
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
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
             *
             * @var  $webInfoRepository WebInfo
             */
            $queryBuilder = $this->applyWebInfoFilter($queryBuilder, $filter);
        }

        $direction = 'ASC';
        if (isset($filter['direction'])
            && in_array(strtoupper($filter['direction']), ['ASC', 'DESC'])
        ) {
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
     * @param array        $filter
     *
     * @return QueryBuilder
     */
    public function applyWebInfoFilter(
        QueryBuilder $queryBuilder,
        array $filter
    ) {
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
        $queryBuilder->select('affiliation_entity_affiliation affiliation');
        $queryBuilder->addSelect('(SELECT
                            COUNT(DISTINCT aff.organisation)
                            FROM Affiliation\Entity\Affiliation aff
                            JOIN aff.organisation org
                            JOIN aff.project pro
                            WHERE org.country = general_entity_country AND aff.dateEnd IS NULL
                            AND pro IN (
                                SELECT proj1 FROM Project\Entity\Version\Version version1 JOIN version1.project proj1 JOIN version1.versionType type1 WHERE type1.id = 2 AND version1.approved = 1
                            ) AND pro NOT IN (
                                 SELECT proj2 FROM Project\Entity\Version\Version version2 JOIN version2.project proj2 JOIN version2.versionType type2 WHERE type2.id = 4
                            )
                            ) partners');
        $queryBuilder->addSelect('(SELECT
                            COUNT(DISTINCT aff2.project)
                            FROM Affiliation\Entity\Affiliation aff2
                            JOIN aff2.organisation org2
                            JOIN aff2.project pro2
                            WHERE org2.country = general_entity_country AND aff2.dateEnd IS NULL
                            AND pro2 IN (
                                SELECT proj3 FROM Project\Entity\Version\Version version3 JOIN version3.project proj3 JOIN version3.versionType type3 WHERE type3.id = 2 AND version3.approved = 1
                            ) AND pro2 NOT IN (
                                SELECT proj4 FROM Project\Entity\Version\Version version4 JOIN version4.project proj4 JOIN version4.versionType type4 WHERE type4.id = 4
                            )
                            ) projects');
        $queryBuilder->from('Affiliation\Entity\Affiliation', 'affiliation_entity_affiliation');
        $queryBuilder->join('affiliation_entity_affiliation.organisation', 'organisation_entity_organisation');
        $queryBuilder->join('affiliation_entity_affiliation.project', 'project_entity_project');
        $queryBuilder->join('organisation_entity_organisation.country', 'general_entity_country');
        //Remove the 0 country (unknown)
        $queryBuilder->where('general_entity_country.id <> 0');
        $queryBuilder->andWhere($queryBuilder->expr()->isNull('affiliation_entity_affiliation.dateEnd'));
        $queryBuilder->addGroupBy('general_entity_country.id');
        $queryBuilder->addOrderBy('general_entity_country.country');
        /**
         * @var $projectRepository \Project\Repository\Project
         */
        $projectRepository = $this->getEntityManager()->getRepository(Project::class);
        $queryBuilder = $projectRepository->onlyActiveProject($queryBuilder);

        //only the active countries
        return $queryBuilder->getQuery()->useQueryCache(true)->useResultCache(true)->getResult();
    }

    /**
     * @param Call $call
     * @param int  $which
     *
     * @throws \InvalidArgumentException
     *
     * @return Entity\Country[]
     */
    public function findCountryByCall(Call $call, $which)
    {
        $queryBuilder = $this->getQueryBuilderForCountryByWhich($which);

        $queryBuilder->andWhere('project_entity_project.call = ?10');
        $queryBuilder->setParameter(10, $call);
        $queryBuilder->addOrderBy('general_entity_country.iso3', 'ASC');

        return $queryBuilder->getQuery()->useQueryCache(true)->getResult();
    }

    /**
     * @param Project $project
     * @param int     $which
     *
     * @throws \InvalidArgumentException
     *
     * @return Entity\Country[]
     */
    public function findCountryByProject(Project $project, $which)
    {
        $queryBuilder = $this->getQueryBuilderForCountryByWhich($which);
        $queryBuilder->andWhere('affiliation_entity_affiliation.project = ?1');
        $queryBuilder->setParameter(1, $project);

        $queryBuilder->addOrderBy('general_entity_country.country', 'ASC');

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
        $findQueryBuilder->select('general_entity_country');
        $findQueryBuilder->from(Project::class, 'project_entity_project');
        $findQueryBuilder->join('project_entity_project.contact', 'contact_entity_contact');
        $findQueryBuilder->join('contact_entity_contact.contactOrganisation', 'contact_entity_contactorganisation');
        $findQueryBuilder->join('contact_entity_contactorganisation.organisation', 'organisation_entity_organisation');
        $findQueryBuilder->join('organisation_entity_organisation.country', 'general_entity_country');
        $findQueryBuilder->andWhere('project_entity_project = ?1');

        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_country2');
        $queryBuilder->from(Entity\Country::class, 'general_entity_country2');
        $queryBuilder->andWhere($queryBuilder->expr()->in('general_entity_country2', $findQueryBuilder->getDQL()));

        $queryBuilder->setParameter(1, $project);

        return $queryBuilder->getQuery()->useResultCache(true)->getOneOrNullResult();
    }

    /**
     * Produces a default query to get a country and the required joins.
     *
     * This one is not folly correct as I need to exclude the still actives as well.
     *
     * @param $which
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderForCountryByWhich($which)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_country');
        $queryBuilder->from(Entity\Country::class, 'general_entity_country');
        $queryBuilder->join('general_entity_country.organisation', 'organisation_entity_organisation');
        $queryBuilder->join('organisation_entity_organisation.affiliation', 'affiliation_entity_affiliation');
        $queryBuilder->join('affiliation_entity_affiliation.project', 'project_entity_project');
        //Remove the 0 country (unknown)
        $queryBuilder->where('general_entity_country.id <> 0');
        $queryBuilder->addGroupBy('general_entity_country.id');
        switch ($which) {
            case AffiliationService::WHICH_ALL:
                break;
            case AffiliationService::WHICH_ONLY_ACTIVE:
                $queryBuilder->andWhere($queryBuilder->expr()->isNull('affiliation_entity_affiliation.dateEnd'));
                break;
            case AffiliationService::WHICH_ONLY_INACTIVE:
                $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('affiliation_entity_affiliation.dateEnd'));

                break;
            default:
                throw new \InvalidArgumentException(sprintf("Incorrect value (%s) for which", $which));
        }

        return $queryBuilder;
    }

    /**
     * @param Call            $call
     * @param Evaluation\Type $type
     *
     * @return Entity\Country[]
     */
    public function findCountryByEvaluationTypeAndCall(
        Evaluation\Type $type,
        Call $call = null
    ) {
        $queryBuilder
            = $this->getQueryBuilderForCountryByWhich(AffiliationService::WHICH_ALL);
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
        $queryBuilder->select('general_entity_country country');
        $queryBuilder->from('General\Entity\Country', 'general_entity_country');
        $queryBuilder->addSelect('(SELECT
                            COUNT(DISTINCT aff.organisation)
                            FROM Affiliation\Entity\Affiliation aff
                            JOIN aff.organisation org
                            JOIN aff.project pro
                            WHERE org.country = general_entity_country AND aff.dateEnd IS NULL
                            AND pro IN (
                                SELECT proj1 FROM Project\Entity\Version\Version version1 JOIN version1.project proj1 JOIN version1.versionType type1 WHERE type1.id = 2 AND version1.approved = 1
                            ) AND pro NOT IN (
                                 SELECT proj2 FROM Project\Entity\Version\Version version2 JOIN version2.project proj2 JOIN version2.versionType type2 WHERE type2.id = 4
                            )
                            ) partners');
        $queryBuilder->addSelect('(SELECT
                            COUNT(DISTINCT aff2.project)
                            FROM Affiliation\Entity\Affiliation aff2
                            JOIN aff2.organisation org2
                            JOIN aff2.project pro2
                            WHERE org2.country = general_entity_country AND aff2.dateEnd IS NULL
                            AND pro2 IN (
                                SELECT proj3 FROM Project\Entity\Version\Version version3 JOIN version3.project proj3 JOIN version3.versionType type3 WHERE type3.id = 2 AND version3.approved = 1
                            ) AND pro2 NOT IN (
                                SELECT proj4 FROM Project\Entity\Version\Version version4 JOIN version4.project proj4 JOIN version4.versionType type4 WHERE type4.id = 4
                            )
                            ) projects');
        $queryBuilder->innerJoin('general_entity_country.itac', 'itac');
        //Remove the 0 country (unknown)
        $queryBuilder->where('general_entity_country.id <> 0');

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
        $query->distinct('general_entity_country.id');
        $query->select('general_entity_country.id');
        $query->addSelect('general_entity_country.country');
        $query->from(Registration::class, 'event_entity_registration');
        $query->where('event_entity_registration.meeting = ?1');
        $query->setParameter(1, $meeting->getId());
        $query->andWhere($query->expr()->isNull('event_entity_registration.dateEnd'));
        $query->andWhere('event_entity_registration.hideInList = ?2');
        $query->andWhere('event_entity_registration.overbooked = ?3');
        $query->setParameter(2, Registration::NOT_HIDE_IN_LIST);
        $query->setParameter(3, Registration::NOT_OVERBOOKED);
        $query->join('event_entity_registration.contact', 'contact_entity_contact');
        $query->join('contact_entity_contact.contactOrganisation', 'contact_entity_contactorganisation');
        $query->join('contact_entity_contactorganisation.organisation', 'organisation_entity_organisation');
        $query->join('organisation_entity_organisation.country', 'general_entity_country');

        return $query->getQuery()->useQueryCache(true)->getResult();
    }

    /**
     * @return array
     */
    public function findForForm()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_country');
        $queryBuilder->from(Entity\Country::class, 'general_entity_country');
        $queryBuilder->addOrderBy('general_entity_country.country');
        /** @var Entity\Country[] $countries */
        $countries = $queryBuilder->getQuery()->useQueryCache(true)->getResult();

        $euCountries = [];
        $restOfWorld = [];

        foreach ($countries as $country) {
            if (!is_null($country->getEu())) {
                $euCountries[$country->getId()] = $country;
            } else {
                $restOfWorld[$country->getId()] = $country;
            }
        }

        //Fake a country
        $country = new Entity\Country();
        $country->setCountry('--- Rest of the world');

        return array_merge($euCountries, [0 => $country], $restOfWorld);
    }

    /**
     * @return array
     */
    public function findCountryInProjectLog()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_country');
        $queryBuilder->from(Entity\Country::class, 'general_entity_country');
        $queryBuilder->innerJoin("general_entity_country.projectLog", 'project_entity_log');
        $queryBuilder->orderBy("project_entity_log.country", 'ASC');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
