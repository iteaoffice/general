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
use Doctrine\ORM\Query;
use Event\Entity\Meeting\Meeting;
use General\Entity;
use General\Repository;
use Program\Entity\Call\Call;
use Project\Entity\Evaluation;
use Project\Entity\Project;
use Project\Entity\Result\Result;
use Zend\Http\Client;
use Zend\Http\Response;
use Zend\Json\Json;

/**
 * Class GeneralService
 * @package General\Service
 */
class GeneralService extends ServiceAbstract
{
    /**
     * @param        $entity
     * @param  array $filter
     *
     * @return Query
     */
    public function findFiltered($entity, array $filter)
    {
        if (is_object($entity)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'No object can be given here for findFiltered: %s',
                    get_class($entity)
                )
            );
        }

        return $this->getEntityManager()->getRepository($entity)->findFiltered($filter);
    }

    /**
     * @param string $entity
     * @param        $docRef
     *
     * @return Entity\EntityAbstract|object
     *
     * @throws \InvalidArgumentException
     */
    public function findEntityByDocRef($entity, $docRef)
    {
        return $this->getEntityManager()->getRepository($entity)->findOneBy(['docRef' => $docRef]);
    }

    /**
     * @return Entity\Challenge[]
     */
    public function findAllChallenges(): array
    {
        return $this->getEntityManager()->getRepository(Entity\Challenge::class)
            ->findBy([], ['challenge' => 'ASC']);
    }

    /**
     * @param string $identifier
     *
     * @return null|object|Entity\EmailMessage
     */
    public function findEmailMessageByIdentifier(string $identifier)
    {
        return $this->getEntityManager()->getRepository(Entity\EmailMessage::class)
            ->findOneBy(['identifier' => $identifier]);
    }

    /**
     * @return Entity\ContentType[]
     */
    public function findContentTypeByImage(): array
    {
        /** @var Repository\ContentType $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\ContentType::class);

        return $repository->findContentTypeByImage();
    }

    /**
     * @return Entity\Country[]
     */
    public function findActiveCountries(): array
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findActive();
    }

    /**
     * @return Entity\Country[]
     */
    public function findCountryInProjectLog(): array
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findCountryInProjectLog();
    }

    /**
     * @return Entity\Country[]
     */
    public function findItacCountries(): array
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findItac();
    }

    /**
     * @param string $iso3
     *
     * @return null|Entity\Country|object
     *
     * @throws \InvalidArgumentException
     */
    public function findCountryByIso3($iso3):?Entity\Country
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findOneBy(['iso3' => strtoupper($iso3)]);
    }

    /**
     * @param $gender
     *
     * @return null|Entity\Gender|object
     */
    public function findGenderByGender($gender):?Entity\Gender
    {
        return $this->getEntityManager()->getRepository(Entity\Gender::class)->findOneBy(['gender' => $gender]);
    }

    /**
     * @param $title
     *
     * @return null|Entity\Title|object
     */
    public function findTitleByTitle($title):?Entity\Title
    {
        return $this->getEntityManager()->getRepository(Entity\Title::class)->findOneBy(['attention' => $title]);
    }

    /**
     * @param string $name
     *
     * @return null|Entity\Country|object
     *
     * @throws \InvalidArgumentException
     */
    public function findCountryByName($name):?Entity\Country
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findOneBy(['country' => $name]);
    }

    /**
     * @param $cd
     *
     * @return null|Entity\Country|object
     *
     * @throws \InvalidArgumentException
     */
    public function findCountryByCD($cd):?Entity\Country
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findOneBy(['cd' => strtoupper($cd)]);
    }

    /**
     * @param Meeting $meeting
     *
     * @return array
     */
    public function findCountriesByMeeting(Meeting $meeting): array
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findCountriesByMeeting($meeting);
    }

    /**
     * @param Call $call
     * @param int $which
     *
     * @return Entity\Country[]
     */
    public function findCountryByCall(
        Call $call,
        $which = AffiliationService::WHICH_ONLY_ACTIVE
    ): array {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findCountryByCall($call, $which);
    }

    /**
     * @param Project $project
     * @param int $which
     *
     * @return Entity\Country[]|ArrayCollection;
     */
    public function findCountryByProject(
        Project $project,
        $which = AffiliationService::WHICH_ONLY_ACTIVE
    ): ArrayCollection {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return new ArrayCollection($repository->findCountryByProject($project, $which));
    }

    /**
     * Returns the country of the project leader (project.contact).
     *
     * @param Project $project
     *
     * @return null|Entity\Country
     */
    public function findCountryOfProjectContact(Project $project):?Entity\Country
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findCountryOfProjectContact($project);
    }

    /**
     * Produce a list of countries active in a call and evaluation type.
     *
     * @param Evaluation\Type $type
     * @param Call|null $call
     *
     * @return Entity\Country[]
     */
    public function findCountryByEvaluationTypeAndCall(
        Evaluation\Type $type,
        Call $call = null
    ): array {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findCountryByEvaluationTypeAndCall($type, $call);
    }

    /**
     * @param $info
     * @return Entity\WebInfo|null|object
     */
    public function findWebInfoByInfo($info): ?Entity\WebInfo
    {
        return $this->getEntityManager()->getRepository(Entity\WebInfo::class)->findOneBy(['info' => $info]);
    }

    /**
     * @param $contentTypeName
     * @return Entity\ContentType
     */
    public function findContentTypeByContentTypeName($contentTypeName): Entity\ContentType
    {
        /** @var Entity\ContentType $entity */
        $contentType = $this->getEntityManager()->getRepository(Entity\ContentType::class)
            ->findOneBy(['contentType' => $contentTypeName]);

        //Create a fallback to the unknown type when the requested type cannot be found.
        if (is_null($contentType)) {
            /** @var Entity\ContentType $contentType */
            $contentType = $this->getEntityManager()->getRepository(Entity\ContentType::class)
                ->find(Entity\ContentType::TYPE_UNKNOWN);
        }

        return $contentType;
    }

    /**
     * Give the location of a user based on an IP address IPAddress of a person by checking an online service.
     *
     * @return null|Entity\Country|object
     */
    public function findLocationByIPAddress()
    {
        $client = new Client();
        $client->setUri(sprintf($this->getModuleOptions()->getGeoIpServiceURL(), $_SERVER['REMOTE_ADDR']));
        if ($client->send()->getStatusCode() === Response::STATUS_CODE_200) {
            /*
             * We have the country, try to find the country in our database
             */
            $countryResult = Json::decode($client->send()->getContent());

            /** @var Repository\Country $repository */
            $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

            return $repository->findOneBy(['cd' => $countryResult->country_code]);
        }

        return $this->findEntityById(Entity\Country::class, 0); //Unknown
    }

    /**
     * @param Entity\Currency $currency
     * @return bool
     */
    public function canDeleteCurrency(Entity\Currency $currency): bool
    {
        return $currency->getContract()->isEmpty();
    }

    /**
     * @param $id
     *
     * @return \General\Entity\Challenge|object
     */
    public function findChallengeById($id): Entity\Challenge
    {
        return $this->getEntityManager()->getRepository(Entity\Challenge::class)->find($id);
    }

    /**
     * @param Result $result
     * @return array|Entity\Challenge[]
     */
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
