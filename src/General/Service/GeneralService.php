<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    Content
 * @package     Service
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\Service;

use Affiliation\Service\AffiliationService;
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
 * GeneralService
 *
 * This is a general service which contains methods which are generally available in this module
 */
class GeneralService extends ServiceAbstract
{
    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @param string $entity
     * @param        $docRef
     *
     * @return Entity\Challenge|Entity\Country
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

        $entity = $this->getEntityManager()->getRepository($this->getFullEntityName($entity))->findOneBy(
            array('docRef' => $docRef)
        );

        return $entity;
    }

    /**
     * @return Entity\Country[]
     */
    public function findActiveCountries()
    {
        return $this->getEntityManager()->getRepository($this->getFullEntityName('country'))->findActive();
    }

    /**
     * @return Entity\Country[]
     */
    public function findItacCountries()
    {
        return $this->getEntityManager()->getRepository($this->getFullEntityName('country'))->findItac();
    }

    /**
     * @param $iso3
     *
     * @return null|Entity\Country
     * @throws \InvalidArgumentException
     */
    public function findCountryByIso3($iso3)
    {
        if (is_null($iso3)) {
            throw new \InvalidArgumentException("A name is required to find an entity");
        }

        $entity = $this->getEntityManager()->getRepository($this->getFullEntityName('country'))->findOneBy(
            array('iso3' => strtoupper($iso3))
        );

        return $entity;
    }

    /**
     * @param $cd
     *
     * @return null|Entity\Country
     * @throws \InvalidArgumentException
     */
    public function findCountryByCD($cd)
    {
        if (is_null($cd)) {
            throw new \InvalidArgumentException("A name is required to find an entity");
        }

        $entity = $this->getEntityManager()->getRepository($this->getFullEntityName('country'))->findOneBy(
            array('cd' => strtoupper($cd))
        );

        return $entity;
    }

    /**
     * @param Meeting $meeting
     *
     * @return array
     */
    public function findCountriesByMeeting(Meeting $meeting)
    {
        return $this->getEntityManager()->getRepository($this->getFullEntityName('country'))
                    ->findCountriesByMeeting($meeting);
    }

    /**
     * Produce a list of countries active in a program call
     *
     * @param Call $call
     * @param int  $which
     *
     * @return \Doctrine\ORM\Query
     */
    public function findCountryByCall(Call $call, $which = AffiliationService::WHICH_ONLY_ACTIVE)
    {
        return $this->getEntityManager()->getRepository(
            $this->getFullEntityName('country')
        )->findCountryByCall($call, $which);
    }

    /**
     * @param Project $project
     * @param int     $which
     *
     * @return Entity\Country[]
     */
    public function findCountryByProject(Project $project, $which = AffiliationService::WHICH_ONLY_ACTIVE)
    {
        return $this->getEntityManager()->getRepository(
            $this->getFullEntityName('country')
        )->findCountryByProject($project, $which);
    }

    /**
     * Produce a list of countries active in a call and evaluation type
     *
     * @param Evaluation\Type $type
     * @param Call|null       $call
     *
     * @return Entity\Country[]
     */
    public function findCountryByEvaluationTypeAndCall(Evaluation\Type $type, Call $call = null)
    {
        return $this->getEntityManager()->getRepository(
            $this->getFullEntityName('country')
        )->findCountryByEvaluationTypeAndCall($call, $type);
    }

    /**
     * @param $info
     *
     * @return Entity\WebInfo
     * @throws \InvalidArgumentException
     */
    public function findWebInfoByInfo($info)
    {
        if (is_null($info)) {
            throw new \InvalidArgumentException("A info-tag is required to find an entity");
        }

        return $this->getEntityManager()->getRepository($this->getFullEntityName('webInfo'))->findOneBy(
            array('info' => $info)
        );
    }

    /**
     * @param $contentTypeName
     *
     * @return null|\General\Entity\ContentType
     * @throws \InvalidArgumentException
     */
    public function findContentTypeByContentTypeName($contentTypeName)
    {
        if (is_null($contentTypeName)) {
            throw new \InvalidArgumentException("A content type name is required to find an entity");
        }

        $entity = $this->getEntityManager()->getRepository($this->getFullEntityName('contentType'))->findOneBy(
            array('contentType' => $contentTypeName)
        );

        return $entity;
    }

    /**
     * Give the location of a user based on an IP address IPAddress of a person by checking an online service
     *
     * @return null|Entity\Country
     */
    public function findLocationByIPAddress()
    {
        $client = new Client();
        $client->setUri(sprintf($this->getOptions()->getGeoIpServiceURL(), $_SERVER['REMOTE_ADDR']));

        if ($client->send()->getStatusCode() === Response::STATUS_CODE_200) {
            /**
             * We have the country, try to find the country in our database
             */
            $countryResult = Json::decode($client->send()->getContent());

            return $this->getEntityManager()->getRepository($this->getFullEntityName('country'))->findOneBy(
                array('cd' => $countryResult->country_code)
            );
        }

        return $this->findEntityById('country', 0); //Unknown
    }

    /**
     * get options
     *
     * @return ModuleOptions
     */
    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('general_module_options'));
        }

        return $this->options;
    }

    /**
     * @param $options
     *
     * @return GeneralService
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param $id
     *
     * @return \General\Entity\Challenge
     */
    public function findChallengeById($id)
    {
        return $this->getEntityManager()->getRepository($this->getFullEntityName('challenge'))->find($id);
    }
}
