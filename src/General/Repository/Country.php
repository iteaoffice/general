<?php
/**
 * DebraNova copyright message placeholder
 *
 * @category  Contact
 * @package   Repository
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\Repository;

use Affiliation\Service\AffiliationService;
use Doctrine\ORM\EntityRepository;
use Event\Entity\Meeting\Meeting;
use Event\Entity\Registration;
use General\Entity;
use Program\Entity\Call\Call;
use Project\Entity\Evaluation;
use Project\Entity\Project;

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

        return $queryBuilder->getQuery()->getResult();
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

        $queryBuilder->andWhere('p.call = ?10');
        $queryBuilder->setParameter(10, $call);
        $queryBuilder->addOrderBy('c.iso3', 'ASC');

        return $queryBuilder->getQuery()->getResult();
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
        $queryBuilder->andWhere('a.project = ?1');
        $queryBuilder->setParameter(1, $project);

        $queryBuilder->addOrderBy('c.country', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * This function returns the country based on an IN query to avoid the unwanted hydration of the result
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

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * Produces a default query to get a country and the required joins
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
     * @param Call            $call
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
        $queryBuilder->addSelect(
            '(SELECT
                            COUNT(DISTINCT aff.organisation)
                            FROM Affiliation\Entity\Affiliation aff
                            JOIN aff.organisation org WHERE org.country = c AND aff.dateEnd IS NULL) partners'
        );
        $queryBuilder->addSelect(
            '(SELECT
                            COUNT(DISTINCT aff2.project)
                            FROM Affiliation\Entity\Affiliation aff2
                            JOIN aff2.organisation org2 WHERE org2.country = c AND aff2.dateEnd IS NULL) projects'
        );
        $queryBuilder->innerJoin('c.itac', 'itac');
        //Remove the 0 country (unknown)
        $queryBuilder->where('c.id <> 0');

        return $queryBuilder->getQuery()->getResult();
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

        return $query->getQuery()->getResult();
    }
}
