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

use DateTime;
use General\Entity;
use General\Repository;
use Program\Entity\Call\Call;
use Project\Entity\Result\Result;

/**
 * Class GeneralService
 *
 * @package General\Service
 */
class GeneralService extends AbstractService
{
    public function findChallengeByDocRef(string $docRef): ?Entity\Challenge
    {
        return $this->entityManager->getRepository(Entity\Challenge::class)->findOneBy(['docRef' => $docRef]);
    }

    public function truncateLog(): void
    {
        /** @var Repository\Log $repository */
        $repository = $this->entityManager->getRepository(Entity\Log::class);

        $repository->truncateLog();
    }

    public function findActiveExchangeRate(Entity\Currency $currency, DateTime $dateTime = null): ?Entity\ExchangeRate
    {
        if (null === $dateTime) {
            $dateTime = new DateTime();
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

    public function findGenderByGender(string $gender): ?Entity\Gender
    {
        return $this->entityManager->getRepository(Entity\Gender::class)->findOneBy(['gender' => $gender]);
    }

    public function findTitleByTitle(string $title): ?Entity\Title
    {
        return $this->entityManager->getRepository(Entity\Title::class)->findOneBy(['attention' => $title]);
    }

    public function findWebInfoByInfo(string $info): ?Entity\WebInfo
    {
        return $this->entityManager->getRepository(Entity\WebInfo::class)->findOneBy(['info' => $info]);
    }

    public function findContentTypeById(int $id): ?Entity\ContentType
    {
        return $this->entityManager->getRepository(Entity\ContentType::class)->find($id);
    }

    public function findContentTypeByContentTypeName(string $contentTypeName): Entity\ContentType
    {
        /** @var Entity\ContentType $contentType */
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

    public function findContentTypeByContentTypeDescription(string $description): Entity\ContentType
    {
        /** @var Entity\ContentType $contentType */
        $contentType = $this->entityManager->getRepository(Entity\ContentType::class)
            ->findOneBy(['description' => $description]);

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

    public function findChallengeById(int $id): ?Entity\Challenge
    {
        return $this->entityManager->getRepository(Entity\Challenge::class)->find($id);
    }

    public function findChallengesByCall(Call $call): array
    {
        $challenges = [];

        /** @var Entity\Challenge $challenge */
        foreach ($this->findActiveForCallsChallenges() as $challenge) {
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

    public function findActiveForCallsChallenges(): array
    {
        return $this->entityManager->getRepository(Entity\Challenge::class)
            ->findActiveForCallsChallenges();
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
