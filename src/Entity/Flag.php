<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Entity for the General.
 *
 * @ORM\Table(name="country_flag")
 * @ORM\Entity
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("country_flag")
 *
 * @category General
 */
class Flag extends EntityAbstract
{
    /**
     * @ORM\Column(name="flag_id",type="integer",nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Country", cascade={"persist"}, inversedBy="flag")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id", nullable=false)
     * @Annotation\Type("DoctrineORMModule\Form\Element\EntitySelect")
     * @Annotation\Options({"target_class":"General\Entity\Flag"})
     * @Annotation\Attributes({"label":"txt-country"})
     *
     * @var \General\Entity\Country
     */
    private $country;
    /**
     * @ORM\Column(name="object",type="blob",nullable=true)
     * @Annotation\Type("\Zend\Form\Element\File")
     * @Annotation\Options({"label":"txt-object"})
     *
     * @var resource
     */
    private $object;

    /**
     * Get the corresponding fileName of a file if it was cached
     * Use a dash (-) to make the distinction between the format to avoid the need of an extra folder.
     *
     * @return string
     */
    public function getCacheFileName(): string
    {
        $cacheDir = __DIR__ . '/../../../../../public' . DIRECTORY_SEPARATOR . 'assets' .
            DIRECTORY_SEPARATOR . ITEAOFFICE_HOST . DIRECTORY_SEPARATOR . 'country-flag';

        return $cacheDir . DIRECTORY_SEPARATOR . strtolower($this->getCountry()->getIso3()) . '.png';
    }

    /**
     * Magic Getter.
     *
     * @param $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic Setter.
     *
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * @param $property
     *
     * @return bool
     */
    public function __isset($property)
    {
        return isset($this->$property);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Flag
     */
    public function setId($id): Flag
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @param Country $country
     * @return Flag
     */
    public function setCountry($country): Flag
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return resource
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param resource $object
     * @return Flag
     */
    public function setObject($object): Flag
    {
        $this->object = $object;

        return $this;
    }
}
