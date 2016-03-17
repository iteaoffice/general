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
use Doctrine\ORM\Query;
use Event\Entity\Meeting\Meeting;
use General\Entity;
use General\Options\ModuleOptions;
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
        if (is_null($entity)) {
            throw new \InvalidArgumentException("An entity is required to find an entity");
        }
        if (is_null($docRef)) {
            throw new \InvalidArgumentException("A docRef is required to find an entity");
        }
        $entity = $this->getEntityManager()->getRepository($this->getFullEntityName($entity))
            ->findOneBy(['docRef' => $docRef]);

        return $entity;
    }

    /**
     * @return Entity\ContentType[]
     */
    public function findContentTypeByImage()
    {
        return $this->getEntityManager()->getRepository(Entity\ContentType::class)->findContentTypeByImage();
    }

    /**
     * @return Entity\Country[]
     */
    public function findActiveCountries()
    {
        return $this->getEntityManager()->getRepository(Entity\Country::class)->findActive();
    }

    /**
     * @return Entity\Country[]
     */
    public function findItacCountries()
    {
        return $this->getEntityManager()->getRepository(Entity\Country::class)->findItac();
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
        if (is_null($iso3)) {
            throw new \InvalidArgumentException("A name is required to find an entity");
        }
        $entity = $this->getEntityManager()->getRepository(Entity\Country::class)
            ->findOneBy(['iso3' => strtoupper($iso3)]);

        return $entity;
    }

    /**
     * @param $gender
     *
     * @return null|object
     */
    public function findGenderByGender($gender)
    {
        if (is_null($gender)) {
            throw new \InvalidArgumentException("A gender is required to find an entity");
        }

        return $this->getEntityManager()->getRepository(Entity\Gender::class)->findOneBy(['gender' => $gender]);
    }

    /**
     * @param $title
     *
     * @return null|object
     */
    public function findTitleByTitle($title)
    {
        if (is_null($title)) {
            throw new \InvalidArgumentException("A title is required to find an entity");
        }

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
        if (is_null($name)) {
            throw new \InvalidArgumentException("A name is required to find an entity");
        }

        return $this->getEntityManager()->getRepository(Entity\Country::class)->findOneBy(['country' => $name]);
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
        if (is_null($cd)) {
            throw new \InvalidArgumentException("A name is required to find an entity");
        }
        $entity = $this->getEntityManager()->getRepository(Entity\Country::class)->findOneBy(['cd' => strtoupper($cd)]);

        return $entity;
    }

    /**
     * @param Meeting $meeting
     *
     * @return array
     */
    public function findCountriesByMeeting(Meeting $meeting)
    {
        return $this->getEntityManager()->getRepository(Entity\Country::class)->findCountriesByMeeting($meeting);
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
        return $this->getEntityManager()->getRepository(Entity\Country::class)->findCountryByCall($call, $which);
    }

    /**
     * @param Project $project
     * @param int     $which
     *
     * @return Entity\Country[]
     */
    public function findCountryByProject(
        Project $project,
        $which = AffiliationService::WHICH_ONLY_ACTIVE
    ) {
        return $this->getEntityManager()->getRepository(Entity\Country::class)->findCountryByProject($project, $which);
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
        return $this->getEntityManager()->getRepository(Entity\Country::class)->findCountryOfProjectContact($project);
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
        return $this->getEntityManager()->getRepository(Entity\Country::class)
            ->findCountryByEvaluationTypeAndCall($call, $type);
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
        if (is_null($info)) {
            throw new \InvalidArgumentException("A info-tag is required to find an entity");
        }

        return $this->getEntityManager()->getRepository($this->getFullEntityName('webInfo'))
            ->findOneBy(['info' => $info]);
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
        if (is_null($contentTypeName)) {
            throw new \InvalidArgumentException("A content type name is required to find an entity");
        }
        $entity = $this->getEntityManager()->getRepository($this->getFullEntityName('contentType'))
            ->findOneBy(['contentType' => $contentTypeName]);

        //Create a fallback to the unknown type when the requested type cannot be found.
        if (is_null($entity)) {
            $entity = $this->getEntityManager()->getRepository($this->getFullEntityName('contentType'))
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

            return $this->getEntityManager()->getRepository(Entity\Country::class)
                ->findOneBy(['cd' => $countryResult->country_code]);
        }

        return $this->findEntityById('country', 0); //Unknown
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
