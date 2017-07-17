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
 * @ORM\Table(name="country_eu")
 * @ORM\Entity
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("country_eu")
 *
 * @category General
 */
class Eu extends EntityAbstract
{
    /**
     * @ORM\Column(name="eu_id",type="integer",nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Country", cascade={"persist"}, inversedBy="eu")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id", nullable=false)
     * @Annotation\Type("DoctrineORMModule\Form\Element\EntitySelect")
     * @Annotation\Options({
     *      "target_class":"General\Entity\Country",
     *      "find_method":{
     *          "name":"findForForm",
     *          "params": {
     *              "criteria":{},
     *              "orderBy":{}
     *          }}
     *      }
     * )
     * @Annotation\Attributes({"label":"txt-country"})
     *
     * @var \General\Entity\Country
     */
    private $country;
    /**
     * @ORM\Column(name="date_since",type="date",nullable=true)
     * @Annotation\Type("\Zend\Form\Element\DateTime")
     * @Annotation\Options({"label":"txt-since"})
     *
     * @var string
     */
    private $since;

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
     * @return bool
     */
    public function __isset($property)
    {
        return isset($this->$property);
    }

    /**
     * toString returns the name.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->country;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Eu
     */
    public function setId(int $id): Eu
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
     * @return Eu
     */
    public function setCountry(Country $country): Eu
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getSince(): string
    {
        return $this->since;
    }

    /**
     * @param string $since
     * @return Eu
     */
    public function setSince(string $since): Eu
    {
        $this->since = $since;

        return $this;
    }
}
