<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

declare(strict_types=1);

namespace General\Entity\Challenge;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use General\Entity\EntityAbstract;
use Zend\Form\Annotation;

/**
 * Project.
 *
 * @ORM\Table(name="challenge_type")
 * @ORM\Entity(repositoryClass="General\Repository\Challenge\Type")
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("challenge_type")
 */
class Type extends EntityAbstract
{
    /**
     * @ORM\Column(name="type_id", type="integer", nullable=false)
     * @ORM\Id
     * @Annotation\Type("\Zend\Form\Element\Hidden")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer
     */
    private $id;
    /**
     * @ORM\Column(name="type", type="string", nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-challenge-type-type-label", "help-block":"txt-challenge-type-type-help-block"})
     *
     * @var string
     */
    private $type;
    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-challenge-type-description-label", "help-block":"txt-challenge-type-description-help-block"})
     *
     * @var string
     */
    private $description;
    /**
     * @ORM\Column(name="sequence", type="integer", nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-challenge-type-sequence-label", "help-block":"txt-challenge-type-sequence-help-block"})
     *
     * @var integer
     */
    private $sequence;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\Challenge", cascade={"persist"}, mappedBy="type")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Challenge[]|Collections\ArrayCollection
     */
    private $challenge;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->challenge = new Collections\ArrayCollection();
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
     * @return bool
     */
    public function __isset($property)
    {
        return isset($this->$property);
    }

    /**
     * Force the Type to a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->type;
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
     * @return Type
     */
    public function setId($id): Type
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Type
     */
    public function setType(string $type): Type
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Type
     */
    public function setDescription(string $description): Type
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    /**
     * @param int $sequence
     * @return Type
     */
    public function setSequence($sequence): Type
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\General\Entity\Challenge[]
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * @param Collections\ArrayCollection|\General\Entity\Challenge[] $challenge
     * @return Type
     */
    public function setChallenge($challenge): Type
    {
        $this->challenge = $challenge;

        return $this;
    }
}