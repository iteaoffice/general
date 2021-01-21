<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Entity\Challenge\Idea\Poster;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use General\Entity\AbstractEntity;
use General\Entity\Challenge;
use General\Entity\ContentType;

/**
 * @ORM\Table(name="challenge_idea_poster_image")
 * @ORM\Entity
 */
class Image extends AbstractEntity
{
    /**
     * @ORM\Column(name="image_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\ContentType", cascade={"persist"}, inversedBy="challengeIdeaPosterImage")
     * @ORM\JoinColumn(name="contenttype_id", referencedColumnName="contenttype_id", nullable=false)
     *
     * @var ContentType
     */
    private $contentType;
    /**
     * @ORM\Column(name="image", type="blob", nullable=false)
     *
     * @var resource
     */
    private $image;
    /**
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     *
     * @var DateTime
     */
    private $dateUpdated;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge", cascade={"persist"}, inversedBy="ideaPosterImage")
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="challenge_id", nullable=false)
     *
     * @var Challenge
     */
    private $challenge;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Image
    {
        $this->id = $id;
        return $this;
    }

    public function getContentType(): ?ContentType
    {
        return $this->contentType;
    }

    public function setContentType(?ContentType $contentType): Image
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): Image
    {
        $this->image = $image;
        return $this;
    }

    public function getDateUpdated(): ?DateTime
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(?DateTime $dateUpdated): Image
    {
        $this->dateUpdated = $dateUpdated;
        return $this;
    }

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): Image
    {
        $this->challenge = $challenge;
        return $this;
    }
}
