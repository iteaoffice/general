<?php

/**
 * ITEA copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Contact\Entity\Contact;
use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Laminas\Form\Annotation;

/**
 * @ORM\Table(name="title")
 * @ORM\Entity(repositoryClass="General\Repository\Title")
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectProperty")
 * @Annotation\Name("general_gender")
 */
class Title extends AbstractEntity
{
    public const TITLE_UNKNOWN = 18;
    /**
     * @ORM\Column(name="title_id",type="integer",options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Type("\Laminas\Form\Element\Hidden")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="title",type="string",nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-title"})
     *
     * @var string
     */
    private $name;
    /**
     * @ORM\Column(name="attention",type="string",nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-attention"})
     *
     * @var string
     */
    private $attention;
    /**
     * @ORM\Column(name="salutation",type="string",nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-salutation"})
     *
     * @var string
     */
    private $salutation;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Contact", cascade={"persist"}, mappedBy="title")
     * @Annotation\Exclude()
     *
     * @var Contact[]
     */
    private $contacts;

    public function __construct()
    {
        $this->contacts = new Collections\ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): Title
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Title
    {
        $this->name = $name;
        return $this;
    }

    public function getAttention(): ?string
    {
        return $this->attention;
    }

    public function setAttention(?string $attention): Title
    {
        $this->attention = $attention;
        return $this;
    }

    public function getSalutation(): ?string
    {
        return $this->salutation;
    }

    public function setSalutation(?string $salutation): Title
    {
        $this->salutation = $salutation;
        return $this;
    }

    public function getContacts()
    {
        return $this->contacts;
    }

    public function setContacts($contacts): Title
    {
        $this->contacts = $contacts;
        return $this;
    }
}
