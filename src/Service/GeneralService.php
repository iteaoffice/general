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
use Event\Entity\Meeting\Meeting;
use General\Entity;
use General\Entity\AbstractEntity;
use General\Repository;
use Program\Entity\Call\Call;
use Project\Entity\Evaluation;
use Project\Entity\Project;
use Project\Entity\Result\Result;

/**
 * Class GeneralService
 *
 * @package General\Service
 */
class GeneralService extends AbstractService
{
    public function findEntityByDocRef(string $entity, string $docRef): ?AbstractEntity
    {
        return $this->entityManager->getRepository($entity)->findOneBy(['docRef' => $docRef]);
    }

    public function truncateLog(): void
    {
        /** @var Repository\Log $repository */
        $repository = $this->entityManager->getRepository(Entity\Log::class);

        $repository->truncateLog();
    }

    /**
     * @return Entity\Challenge[]
     */
    public function findAllChallenges(): array
    {
        return $this->entityManager->getRepository(Entity\Challenge::class)
            ->findBy([], ['challenge' => 'ASC']);
    }

    public function findActiveExchangeRate(Entity\Currency $currency, \DateTime $dateTime = null): ?Entity\ExchangeRate
    {
        if (null === $dateTime) {
            $dateTime = new \DateTime();
        }

        /**
         * Iterate over the exchange rate and return the one as soon as the date is lower than today
         */
        foreach ($currency->getExchangeRate() as $exchangeRate) {
            if ($exchangeRate->getDate() < $dateTime) {
                return $exchangeRate;
            }
        }

        return null;
    }

    public function findEmailMessageByIdentifier(string $identifier): ?Entity\EmailMessage
    {
        return $this->entityManager->getRepository(Entity\EmailMessage::class)
            ->findOneBy(['identifier' => $identifier]);
    }

    /**
     * @return Entity\ContentType[]
     */
    public function findContentTypeByImage(): array
    {
        /** @var Repository\ContentType $repository */
        $repository = $this->entityManager->getRepository(Entity\ContentType::class);

        return $repository->findContentTypeByImage();
    }

    /**
     * @return Entity\Country[]
     */
    public function findActiveCountries(): array
    {
        /** @var Repository\Country $repository */
        $repository = $this->entityManager->getRepository(Entity\Country::class);

        return $repository->findActive();
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

    /**
     * @return Entity\Country[]
     */
    public function findItacCountries(): array
    {
        /** @var Repository\Country $repository */
        $repository = $this->entityManager->getRepository(Entity\Country::class);

        return $repository->findItac();
    }

    public function findCountryByIso3(string $iso3): ?Entity\Country
    {
        return $this->entityManager->getRepository(Entity\Country::class)->findOneBy(['iso3' => strtoupper($iso3)]);
    }

    public function findGenderByGender(string $gender): ?Entity\Gender
    {
        return $this->entityManager->getRepository(Entity\Gender::class)->findOneBy(['gender' => $gender]);
    }

    public function findTitleByTitle(string $title): ?Entity\Title
    {
        return $this->entityManager->getRepository(Entity\Title::class)->findOneBy(['attention' => $title]);
    }

    public function findCountryByName(string $name): ?Entity\Country
    {
        return $this->entityManager->getRepository(Entity\Country::class)->findOneBy(['country' => $name]);
    }

    public function findCountryByCD(string $cd): ?Entity\Country
    {
        return $this->entityManager->getRepository(Entity\Country::class)->findOneBy(['cd' => strtoupper($cd)]);
    }

    /**
     * @param Meeting $meeting
     *
     * @return Entity\Country[]
     */
    public function findCountriesByMeeting(Meeting $meeting): array
    {
        $repository = $this->entityManager->getRepository(Entity\Country::class);

        return $repository->findCountriesByMeeting($meeting);
    }

    /**
     * @param Call $call
     * @param int  $which
     *
     * @return Entity\Country[]
     */
    public function findCountryByCall(
        Call $call,
        $which = AffiliationService::WHICH_ONLY_ACTIVE
    ): array {
        /** @var Repository\Country $repository */
        $repository = $this->entityManager->getRepository(Entity\Country::class);

        return $repository->findCountryByCall($call, $which);
    }

    /**
     * @param Project $project
     * @param int     $which
     *
     * @return Entity\Country[]|ArrayCollection;
     */
    public function findCountryByProject(
        Project $project,
        $which = AffiliationService::WHICH_ONLY_ACTIVE
    ): ArrayCollection {
        /** @var Repository\Country $repository */
        $repository = $this->entityManager->getRepository(Entity\Country::class);

        return new ArrayCollection($repository->findCountryByProject($project, $which));
    }

    /**
     * Returns the country of the project leader (project.contact).
     *
     * @param Project $project
     *
     * @return null|Entity\Country
     */
    public function findCountryOfProjectContact(Project $project): ?Entity\Country
    {
        /** @var Repository\Country $repository */
        $repository = $this->entityManager->getRepository(Entity\Country::class);

        return $repository->findCountryOfProjectContact($project);
    }

    /**
     * Produce a list of countries active in a call and evaluation type.
     *
     * @param Evaluation\Type $type
     * @param Call|null       $call
     *
     * @return Entity\Country[]
     */
    public function findCountryByEvaluationTypeAndCall(
        Evaluation\Type $type,
        Call $call = null
    ): array {
        /** @var Repository\Country $repository */
        $repository = $this->entityManager->getRepository(Entity\Country::class);

        return $repository->findCountryByEvaluationTypeAndCall($type, $call);
    }

    public function findWebInfoByInfo(string $info): ?Entity\WebInfo
    {
        return $this->entityManager->getRepository(Entity\WebInfo::class)->findOneBy(['info' => $info]);
    }

    public function findContentTypeByContentTypeName(string $contentTypeName): Entity\ContentType
    {
        /** @var Entity\ContentType $entity */
        $contentType = $this->entityManager->getRepository(Entity\ContentType::class)
            ->findOneBy(['contentType' => $contentTypeName]);

        //Create a fallback to the unknown type when the requested type cannot be found.
        if (null === $contentType) {
            /** @var Entity\ContentType $contentType */
            return $this->entityManager->getRepository(Entity\ContentType::class)->find(
                Entity\ContentType::TYPE_UNKNOWN
            );
        }

        return $contentType;
    }

    public function canDeleteCurrency(Entity\Currency $currency): bool
    {
        return $currency->getContract()->isEmpty();
    }

    public function findChallengeById(int $id): Entity\Challenge
    {
        return $this->entityManager->getRepository(Entity\Challenge::class)->find($id);
    }

    public function findChallengesByCall(Call $call): array
    {
        $challenges = [];

        /** @var Entity\Challenge $challenge */
        foreach ($this->findAll(Entity\Challenge::class) as $challenge) {
            if ($challenge->getCall()->isEmpty()) {
                $challenges[$challenge->getId()] = $challenge;
            }

            //Add the challenges which have the same call
            foreach ($challenge->getCall() as $challengeCall) {
                if ($challengeCall->getId() === $call->getId()) {
                    $challenges[$challenge->getId()] = $challenge;
                }
            }
        }

        return $challenges;
    }

    public function parseChallengesByResult(Result $result): array
    {
        $challenges = [];
        //Add the challenge fromm the project
        foreach ($result->getProject()->getProjectChallenge() as $projectChallenge) {
            $challenge = $projectChallenge->getChallenge();

            $challenges[$challenge->getId()] = $challenge;
        }

        //add the challenge from the result
        foreach ($result->getChallenge() as $challenge) {
            $challenges[$challenge->getId()] = $challenge;
        }

        return $challenges;
    }
}
