<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Laminas\Form\Annotation;

/**
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="General\Repository\Language")
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectPropertyHydrator")
 * @Annotation\Name("language")
 */
class Language extends AbstractEntity
{
    /**
     * @ORM\Column(name="language_id",type="integer",options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Type("\Laminas\Form\Element\Hidden")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="language",type="string",unique=true)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-language-name-label","help-block":"txt-language-name-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-language-name-placeholder"})
     *
     * @var string
     */
    private $language;
    /**
     * @ORM\Column(name="locale",type="string",unique=true)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-language-locale-label","help-block":"txt-language-locale-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-language-locale-placeholder"})
     *
     * @var string
     */
    private $locale;
    /**
     * @ORM\Column(name="date_created", type="datetime",nullable=false)
     * @Gedmo\Timestampable(on="create")
     * @Annotation\Exclude()
     *
     * @var \DateTime
     */
    private $dateCreated;
    /**
     * @ORM\Column(name="last_update", type="datetime",nullable=true)
     * @Gedmo\Timestampable(on="update")
     * @Annotation\Exclude()
     *
     * @var \DateTime
     */
    private $lastUpdate;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\AdvisoryBoard\Tender", cascade={"persist"}, mappedBy="language")
     * @Annotation\Exclude()
     *
     * @var \Organisation\Entity\AdvisoryBoard\Tender[]|Collections\ArrayCollection()
     */
    private $advisoryBoardTenders;

    public function __construct()
    {
        $this->advisoryBoardTenders = new Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Language
    {
        $this->id = $id;
        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): Language
    {
        $this->language = $language;
        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): Language
    {
        $this->locale = $locale;
        return $this;
    }

    public function getDateCreated(): ?\DateTime
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?\DateTime $dateCreated): Language
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function getLastUpdate(): ?\DateTime
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(?\DateTime $lastUpdate): Language
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    public function getAdvisoryBoardTenders()
    {
        return $this->advisoryBoardTenders;
    }

    public function setAdvisoryBoardTenders($advisoryBoardTenders): Language
    {
        $this->advisoryBoardTenders = $advisoryBoardTenders;
        return $this;
    }
}
