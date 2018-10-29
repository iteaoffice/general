<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Content
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Service;

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
use Solarium\Core\Query\AbstractQuery;

/**
 * Class CountryService
 *
 * @package General\Service
 */
class CountryService extends AbstractService implements SearchUpdateInterface
{
    /**
     * @var CountrySearchService
     */
    private $countrySearchService;
    /**
     * @var ProjectService
     */
    private $projectService;
    /**
     * @var AffiliationService
     */
    private $affiliationService;

    public function __construct(
        EntityManager $entityManager,
        CountrySearchService $countrySearchService,
        ProjectService $projectService,
        AffiliationService $affiliationService
    ) {
        parent::__construct($entityManager);

        $this->countrySearchService = $countrySearchService;
        $this->projectService = $projectService;
        $this->affiliationService = $affiliationService;
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

    public function getAffiliationCountries(
        Project $project,
        $which = AffiliationService::WHICH_ONLY_ACTIVE
    ): array {
        return $this->affiliationService->findAffiliationCountriesByProjectAndWhich($project, $which);
    }

    public function findCountryByCall(
        Call $call,
        int $which = AffiliationService::WHICH_ONLY_ACTIVE
    ): array {
        $repository = $this->entityManager->getRepository(Entity\Country::class);

        return $repository->findCountryByCall($call, $which);
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
        $countries = $this->findAll(Entity\Country::class);
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
        $searchClient = new Client();
        $update = $searchClient->createUpdate();

        // Add the country data as the document
        $countryDocument = $update->createDocument();
        // Country properties
        $countryDocument->id = $country->getResourceId();

        $countryDocument->country_id = $country->getId();
        $countryDocument->country = $country->getCountry();
        $countryDocument->country_cd = $country->getCd();
        $countryDocument->country_iso3 = $country->getIso3();
        $countryDocument->country_sort = $country->getCountry();
        $countryDocument->country_search = $country->getCountry();

        $countryDocument->docref = $country->getDocRef();

        $countryDocument->is_itac = $country->isItac();
        $countryDocument->is_itac_text = $country->isItac() ? 'YES' : 'NO';
        $countryDocument->is_eu = $country->isEu();
        $countryDocument->is_eu_text = $country->isEu() ? 'YES' : 'NO';
        $countryDocument->is_eureka = $country->isEureka();
        $countryDocument->is_eureka_text = $country->isEureka() ? 'YES' : 'NO';


        //Find all the projects and partners
        $projects = [];
        $affiliations = [];

        foreach ($country->getOrganisation() as $organisation) {
            foreach ($organisation->getAffiliation() as $affiliation) {
                if (!$affiliation->isActive()) {
                    continue;
                }

                $project = $affiliation->getProject();
                if (!$this->projectService->onWebsite($project)) {
                    continue;
                }

                $projectId = $project->getId();
                $affiliationId = $affiliation->getId();

                $projects[$projectId] = $projectId;
                $affiliations[$affiliationId] = $affiliationId;
            }
        }


        $countryDocument->projects = \count($projects);
        $countryDocument->has_projects = \count($projects) > 0;
        $countryDocument->affiliations = \count($affiliations);
        $countryDocument->has_affiliations = \count($affiliations) > 0;
        $countryDocument->funders = $country->getFunder()->filter(
            function (Funder $funder) {
                return $funder->getShowOnWebsite() === Funder::SHOW_ON_WEBSITE;
            }
        )->count();

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
