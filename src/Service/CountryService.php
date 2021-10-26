<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Service;

use Affiliation\Entity\Affiliation;
use Affiliation\Service\AffiliationService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use General\Entity;
use General\Entity\AbstractEntity;
use General\Repository;
use General\Search\Service\CountrySearchService;
use Program\Entity\Call\Call;
use Program\Entity\Funder;
use Project\Entity\Project;
use Project\Service\ProjectService;
use Search\Service\SearchUpdateInterface;
use Solarium\Client;
use Solarium\Core\Client\Adapter\Http;
use Solarium\Core\Query\AbstractQuery;
use Solarium\QueryType\Update\Query\Document;

use Symfony\Component\EventDispatcher\EventDispatcher;

use function count;

/**
 * Class CountryService
 *
 * @package General\Service
 */
class CountryService extends AbstractService implements SearchUpdateInterface
{
    private CountrySearchService $countrySearchService;
    private ProjectService $projectService;

    public function __construct(
        EntityManager $entityManager,
        CountrySearchService $countrySearchService,
        ProjectService $projectService
    ) {
        parent::__construct($entityManager);

        $this->countrySearchService = $countrySearchService;
        $this->projectService       = $projectService;
    }

    /**
     * @return Entity\Country[]
     */
    public function findCountryInProjectLog(): array
    {
        /** @var Repository\Country $repository */
        $repository = $this->entityManager->getRepository(Entity\Country::class);

        return $repository->findCountryInProjectLog();
    }

    public function findCountryById(int $id): ?Entity\Country
    {
        return $this->entityManager->getRepository(Entity\Country::class)->find($id);
    }

    public function findByCountryByDocRef(string $docRef): ?Entity\Country
    {
        return $this->entityManager->getRepository(Entity\Country::class)->findOneBy(['docRef' => $docRef]);
    }

    public function findCountryByIso3(string $iso3): ?Entity\Country
    {
        return $this->entityManager->getRepository(Entity\Country::class)->findOneBy(['iso3' => strtoupper($iso3)]);
    }

    public function findCountryByName(string $name): ?Entity\Country
    {
        return $this->entityManager->getRepository(Entity\Country::class)->findOneBy(['country' => $name]);
    }

    public function findCountryByCD(string $cd): ?Entity\Country
    {
        return $this->entityManager->getRepository(Entity\Country::class)->findOneBy(['cd' => strtoupper($cd)]);
    }

    public function findCountryByCall(
        Call $call,
        int $which = AffiliationService::WHICH_ONLY_ACTIVE
    ): array {
        $repository = $this->entityManager->getRepository(Entity\Country::class);

        return $repository->findCountryByCall($call, $which);
    }

    /**
     * Duplicate of AffiliationService::findAffiliationCountriesByProjectAndWhich to avoid circular dependencies
     *
     * @param Project $project
     * @param int $which
     * @return array
     */
    public function getAffiliationCountries(
        Project $project,
        int $which = AffiliationService::WHICH_ONLY_ACTIVE
    ): array {
        $repository = $this->entityManager->getRepository(Affiliation::class);

        /**
         * @var $affiliations Affiliation[]
         */
        $affiliations = $repository->findAffiliationByProjectAndWhich($project, $which);
        $result       = [];
        foreach ($affiliations as $affiliation) {
            $country = $affiliation->getOrganisation()->getCountry();

            $result[$country->getCountry()] = $country;
        }

        ksort($result);

        return $result;
    }

    public function findCountryByProject(
        Project $project,
        $which = AffiliationService::WHICH_ONLY_ACTIVE
    ): ArrayCollection {
        /** @var Repository\Country $repository */
        $repository = $this->entityManager->getRepository(Entity\Country::class);

        return new ArrayCollection($repository->findCountryByProject($project, $which));
    }

    public function delete(AbstractEntity $abstractEntity): void
    {
        if ($abstractEntity instanceof Entity\Country) {
            $this->countrySearchService->deleteDocument($abstractEntity);
        }

        parent::delete($abstractEntity);
    }

    public function updateCollectionInSearchEngine(bool $clearIndex = false): void
    {
        $countries  = $this->findAll(Entity\Country::class);
        $collection = [];

        /** @var Entity\Country $country */
        foreach ($countries as $country) {
            $collection[] = $this->prepareSearchUpdate($country);
        }

        $this->countrySearchService->updateIndexWithCollection($collection, $clearIndex);
    }

    /**
     * @param Entity\Country $country
     *
     * @return AbstractQuery
     */
    public function prepareSearchUpdate($country): AbstractQuery
    {
        $searchClient = new Client(new Http(), new EventDispatcher(), []);
        $update       = $searchClient->createUpdate();

        /** @var Document $countryDocument */
        $countryDocument = $update->createDocument();

        $countryDocument->setField('id', $country->getResourceId());
        $countryDocument->setField('country_id', $country->getId());
        $countryDocument->setField('country', $country->getCountry());
        $countryDocument->setField('country_cd', $country->getCd());
        $countryDocument->setField('country_iso3', $country->getIso3());
        $countryDocument->setField('country_sort', $country->getCountry());
        $countryDocument->setField('country_search', $country->getCountry());

        $countryDocument->setField('docref', $country->getDocRef());

        $countryDocument->setField('is_itac', $country->isItac());
        $countryDocument->setField('is_itac_text', $country->isItac() ? 'Yes' : 'No');
        $countryDocument->setField('is_eu', $country->isEu());
        $countryDocument->setField('is_eu_text', $country->isEu() ? 'Yes' : 'No');
        $countryDocument->setField('is_eureka', $country->isEureka());
        $countryDocument->setField('is_eureka_text', $country->isEureka() ? 'Yes' : 'No');

        //Find all the projects and partners
        $projects     = [];
        $affiliations = [];

        foreach ($country->getOrganisation() as $organisation) {
            foreach ($organisation->getAffiliation() as $affiliation) {
                if (! $affiliation->isActive()) {
                    continue;
                }

                $project = $affiliation->getProject();
                if (! $this->projectService->onWebsite($project)) {
                    continue;
                }

                $projectId     = $project->getId();
                $affiliationId = $affiliation->getId();

                $projects[$projectId]                                   = $projectId;
                $affiliations[$affiliation->getOrganisation()->getId()] = $affiliationId;
            }
        }

        $amountOfFunders = $country->getFunder()->filter(fn (Funder $funder) => $funder->getShowOnWebsite() === Funder::SHOW_ON_WEBSITE)->count();

        $countryDocument->setField('projects', count($projects));
        $countryDocument->setField('has_projects', count($projects) > 0);
        $countryDocument->setField('has_projects_text', count($projects) > 0 ? 'Yes' : 'No');
        $countryDocument->setField('affiliations', count($affiliations));
        $countryDocument->setField('has_affiliations', count($affiliations) > 0);
        $countryDocument->setField('has_affiliations_text', count($affiliations) > 0 ? 'Yes' : 'No');
        $countryDocument->setField('funders', $amountOfFunders);
        $countryDocument->setField('has_funders', $amountOfFunders > 0);
        $countryDocument->setField('has_funders_text', $amountOfFunders > 0 ? 'Yes' : 'No');

        $update->addDocument($countryDocument);
        $update->addCommit();

        return $update;
    }

    public function save(AbstractEntity $abstractEntity): AbstractEntity
    {
        parent::save($abstractEntity);

        if ($abstractEntity instanceof Entity\Country) {
            $this->updateEntityInSearchEngine($abstractEntity);
        }

        return $abstractEntity;
    }

    /**
     * @param Entity\Country $country
     */
    public function updateEntityInSearchEngine($country): void
    {
        $document = $this->prepareSearchUpdate($country);

        $this->countrySearchService->executeUpdateDocument($document);
    }
}
