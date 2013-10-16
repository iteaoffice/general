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

/**
 * GeneralService
 *
 * This is a general service which contains methods which are generally available in this module
 */
class GeneralService extends ServiceAbstract
{
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
     * @param $iso3
     *
     * @return null|Country
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
     * @param $id
     *
     * @return \General\Entity\Challenge
     */
    public function findChallengeById($id)
    {
        return $this->getEntityManager()->getRepository($this->getFullEntityName('challenge'))->find($id);
    }
}
