<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Contact
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Repository;

use Affiliation\Service\AffiliationService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use General\Entity;
use Program\Entity\Call\Call;
use Project\Entity\Project;

/**
 * Class Country
 *
 * @package General\Repository
 */
class Country extends EntityRepository
{
    public function findFiltered(array $filter): QueryBuilder
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_country');
        $queryBuilder->from(Entity\Country::class, 'general_entity_country');

        if (null !== $filter) {
            $queryBuilder = $this->applyFilter($queryBuilder, $filter);
        }

        $direction = 'ASC';
        if (isset($filter['direction'])
            && \in_array(\strtoupper($filter['direction']), ['ASC', 'DESC'], true)
        ) {
            $direction = \strtoupper($filter['direction']);
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

    public function applyFilter(QueryBuilder $queryBuilder, array $filter): QueryBuilder
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

    public function findCountryByCall(Call $call, int $which): array
    {
        $queryBuilder = $this->getQueryBuilderForCountryByWhich($which);

        $queryBuilder->andWhere('project_entity_project.call = ?10');
        $queryBuilder->setParameter(10, $call);
        $queryBuilder->addOrderBy('general_entity_country.iso3', 'ASC');

        return $queryBuilder->getQuery()->useQueryCache(true)->getResult();
    }

    public function getQueryBuilderForCountryByWhich($which): QueryBuilder
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
                throw new \InvalidArgumentException(sprintf('Incorrect value (%s) for which', $which));
        }

        return $queryBuilder;
    }

    public function findCountryByProject(Project $project, int $which): array
    {
        $queryBuilder = $this->getQueryBuilderForCountryByWhich($which);
        $queryBuilder->andWhere('affiliation_entity_affiliation.project = ?1');
        $queryBuilder->setParameter(1, $project);

        $queryBuilder->addOrderBy('general_entity_country.country', 'ASC');

        return $queryBuilder->getQuery()->useQueryCache(true)->getResult();
    }

    public function findAmountOfActiveCountries(): int
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select($queryBuilder->expr()->countDistinct('general_entity_country'));
        $queryBuilder->from(Entity\Country::class, 'general_entity_country');
        $queryBuilder->innerJoin('general_entity_country.organisation', 'organisation_entity_organisation');

        $queryBuilder->innerJoin('organisation_entity_organisation.affiliation', 'affiliation_entity_affiliation');
        $queryBuilder->innerJoin('affiliation_entity_affiliation.project', 'project_entity_project');

        $projectRepository = $this->_em->getRepository(\Project\Entity\Project::class);
        $queryBuilder = $projectRepository->onlyActiveProject($queryBuilder);

        $queryBuilder->andWhere($queryBuilder->expr()->isNull('affiliation_entity_affiliation.dateEnd'));

        return (int)$queryBuilder->getQuery()->useQueryCache(true)->useResultCache(true)->getSingleScalarResult();
    }

    public function findForForm(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_country');
        $queryBuilder->from(Entity\Country::class, 'general_entity_country');
        $queryBuilder->addOrderBy('general_entity_country.country');
        /** @var Entity\Country[] $countries */
        $countries = $queryBuilder->getQuery()->useQueryCache(true)->getResult();

        //Fake a country
        $country = new Entity\Country();
        $country->setCountry('--- Select a country');

        $euCountries = [0 => $country];
        $restOfWorld = [];

        foreach ($countries as $country) {
            if (null !== $country->getEu()) {
                $euCountries[$country->getId()] = $country;
            } else {
                $restOfWorld[$country->getId()] = $country;
            }
        }


        return \array_merge($euCountries, $restOfWorld);
    }

    public function findForFormNoEmptyOption(): array
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
            if (null !== $country->getEu()) {
                $euCountries[$country->getId()] = $country;
            } else {
                $restOfWorld[$country->getId()] = $country;
            }
        }


        return \array_merge($euCountries, $restOfWorld);
    }

    public function findCountryInProjectLog(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_country');
        $queryBuilder->from(Entity\Country::class, 'general_entity_country');
        $queryBuilder->innerJoin('general_entity_country.projectLog', 'project_entity_log');
        $queryBuilder->orderBy('general_entity_country.country', 'ASC');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
