<?php

/**
 * ITEA Office all rights reserved
 *
 * @category  Contact
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Repository;

use Affiliation\Service\AffiliationService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use General\Entity;
use InvalidArgumentException;
use Program\Entity\Call\Call;
use Project\Entity\Project;

use function array_merge;

/**
 * Class Country
 *
 * @package General\Repository
 */
class Country extends EntityRepository
{
    public function findCountryByCall(Call $call, int $which): array
    {
        $queryBuilder = $this->getQueryBuilderForCountryByWhich($which);

        $queryBuilder->andWhere('project_entity_project.call = :call');
        $queryBuilder->setParameter('call', $call);
        $queryBuilder->addOrderBy('general_entity_country.iso3', Criteria::ASC);

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
                throw new InvalidArgumentException(sprintf('Incorrect value (%s) for which', $which));
        }

        return $queryBuilder;
    }

    public function findCountryByProject(Project $project, int $which): array
    {
        $queryBuilder = $this->getQueryBuilderForCountryByWhich($which);
        $queryBuilder->andWhere('affiliation_entity_affiliation.project = ?1');
        $queryBuilder->setParameter(1, $project);

        $queryBuilder->addOrderBy('general_entity_country.country', Criteria::ASC);

        return $queryBuilder->getQuery()->useQueryCache(true)->getResult();
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


        return array_merge($euCountries, $restOfWorld);
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

        return array_merge($euCountries, $restOfWorld);
    }

    public function findCountryInProjectLog(): array
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('general_entity_country');
        $queryBuilder->from(Entity\Country::class, 'general_entity_country');
        $queryBuilder->innerJoin('general_entity_country.projectLog', 'project_entity_log');
        $queryBuilder->orderBy('general_entity_country.country', Criteria::ASC);

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
