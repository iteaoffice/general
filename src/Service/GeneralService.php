<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  Content
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

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
use Zend\Http\Client;
use Zend\Http\Response;
use Zend\Json\Json;

/**
 * GeneralService.
 *
 * This is a general service which contains methods which are generally available in this module
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
            throw new \InvalidArgumentException(sprintf(
                'No object can be given here for findFiltered: %s',
                get_class($entity)
            ));
        }

        return $this->getEntityManager()->getRepository($entity)->findFiltered($filter);
    }

    /**
     * @param string $entity
     * @param        $docRef
     *
     * @return Entity\Challenge|Entity\Country
     *
     * @throws \InvalidArgumentException
     */
    public function findEntityByDocRef($entity, $docRef)
    {
        return $this->getEntityManager()->getRepository($entity)->findOneBy(['docRef' => $docRef]);
    }

    /**
     * @return Entity\ContentType[]
     */
    public function findContentTypeByImage()
    {
        /** @var Repository\ContentType $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\ContentType::class);

        return $repository->findContentTypeByImage();
    }

    /**
     * @return Entity\Country[]
     */
    public function findActiveCountries()
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findActive();
    }

    /**
     * @return Entity\Country[]
     */
    public function findCountryInProjectLog()
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findCountryInProjectLog();
    }

    /**
     * @return Entity\Country[]
     */
    public function findItacCountries()
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findItac();
    }

    /**
     * @param string $iso3
     *
     * @return null|Entity\Country
     *
     * @throws \InvalidArgumentException
     */
    public function findCountryByIso3($iso3)
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findOneBy(['iso3' => strtoupper($iso3)]);
    }

    /**
     * @param $gender
     *
     * @return null|Entity\Gender
     */
    public function findGenderByGender($gender)
    {
        return $this->getEntityManager()->getRepository(Entity\Gender::class)->findOneBy(['gender' => $gender]);
    }

    /**
     * @param $title
     *
     * @return null|Entity\Title
     */
    public function findTitleByTitle($title)
    {
        return $this->getEntityManager()->getRepository(Entity\Title::class)->findOneBy(['title' => $title]);
    }

    /**
     * @param string $name
     *
     * @return null|Entity\Country
     *
     * @throws \InvalidArgumentException
     */
    public function findCountryByName($name)
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findOneBy(['country' => $name]);
    }

    /**
     * @param $cd
     *
     * @return null|Entity\Country
     *
     * @throws \InvalidArgumentException
     */
    public function findCountryByCD($cd)
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
    public function findCountriesByMeeting(Meeting $meeting)
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findCountriesByMeeting($meeting);
    }

    /**
     * Produce a list of countries active in a program call.
     *
     * @param Call $call
     * @param int  $which
     *
     * @return \Doctrine\ORM\Query
     */
    public function findCountryByCall(
        Call $call,
        $which = AffiliationService::WHICH_ONLY_ACTIVE
    ) {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

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
    ) {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return new ArrayCollection($repository->findCountryByProject($project, $which));
    }

    /**
     * Returns the country of the project leader (project.contact).
     *
     * @param Project $project
     *
     * @return null|Entity\Country[]
     */
    public function findCountryOfProjectContact(Project $project)
    {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

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
    ) {
        /** @var Repository\Country $repository */
        $repository = $this->getEntityManager()->getRepository(Entity\Country::class);

        return $repository->findCountryByEvaluationTypeAndCall($type, $call);
    }

    /**
     * @param $info
     *
     * @return Entity\WebInfo
     *
     * @throws \InvalidArgumentException
     */
    public function findWebInfoByInfo($info)
    {
        return $this->getEntityManager()->getRepository(Entity\WebInfo::class)->findOneBy(['info' => $info]);
    }

    /**
     * @param $contentTypeName
     *
     * @return null|Entity\ContentType
     *
     * @throws \InvalidArgumentException
     */
    public function findContentTypeByContentTypeName($contentTypeName)
    {
        $entity = $this->getEntityManager()->getRepository(Entity\ContentType::class)
            ->findOneBy(['contentType' => $contentTypeName]);

        //Create a fallback to the unknown type when the requested type cannot be found.
        if (is_null($entity)) {
            $entity = $this->getEntityManager()->getRepository(Entity\ContentType::class)
                ->find(Entity\ContentType::TYPE_UNKNOWN);
        }

        return $entity;
    }

    /**
     * Give the location of a user based on an IP address IPAddress of a person by checking an online service.
     *
     * @return null|Entity\Country
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
     * @param $id
     *
     * @return \General\Entity\Challenge
     */
    public function findChallengeById($id)
    {
        return $this->getEntityManager()->getRepository(Entity\Challenge::class)->find($id);
    }
}
