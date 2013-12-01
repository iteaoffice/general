<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    Content
 * @package     Service
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */
namespace General\Service;

use General\Entity;
use General\Options\ModuleOptions;
use Zend\Http\Client;
use Zend\Json\Json;
use Zend\Http\Response;

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
     * @param $entity
     * @param $docRef
     *
     * @return null|object
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
        $countries = $this->getEntityManager()->getRepository($this->getFullEntityName('country'))->findActive();

        return $countries;
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
     * @param $id
     *
     * @return \General\Entity\Challenge
     */
    public function findChallengeById($id)
    {
        return $this->getEntityManager()->getRepository($this->getFullEntityName('challenge'))->find($id);
    }

    /**
     * @param $options
     *
     * @return ModuleOptions
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
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
}
