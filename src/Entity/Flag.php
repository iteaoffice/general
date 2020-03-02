<?php

/**
 * ITEA Office all rights reserved
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;
use Laminas\Form\Annotation;

/**
 * @ORM\Table(name="country_flag")
 * @ORM\Entity
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectPropertyHydrator")
 * @Annotation\Name("country_flag")
 */
class Flag extends AbstractEntity
{
    /**
     * @ORM\Column(name="flag_id",type="integer",options={"unsigned":true})
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
     * @var Country
     */
    private $country;
    /**
     * @ORM\Column(name="object",type="blob",nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\File")
     * @Annotation\Options({"label":"txt-object"})
     *
     * @var resource
     */
    private $object;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Flag
    {
        $this->id = $id;
        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): Flag
    {
        $this->country = $country;
        return $this;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setObject($object): Flag
    {
        $this->object = $object;
        return $this;
    }
}
