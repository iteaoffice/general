<?php

/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
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
 * @ORM\Table(name="challenge_idea_poster_icon")
 * @ORM\Entity
 */
class Icon extends AbstractEntity
{
    /**
     * @ORM\Column(name="icon_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\ContentType", cascade={"persist"}, inversedBy="challengeIdeaPosterIcon")
     * @ORM\JoinColumn(name="contenttype_id", referencedColumnName="contenttype_id", nullable=false)
     *
     * @var ContentType
     */
    private $contentType;
    /**
     * @ORM\Column(name="icon", type="blob", nullable=false)
     *
     * @var resource
     */
    private $icon;
    /**
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     *
     * @var DateTime
     */
    private $dateUpdated;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge", cascade={"persist"}, inversedBy="ideaPosterIcon")
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="challenge_id", nullable=false)
     *
     * @var Challenge
     */
    private $challenge;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Icon
    {
        $this->id = $id;
        return $this;
    }

    public function getContentType(): ?ContentType
    {
        return $this->contentType;
    }

    public function setContentType(?ContentType $contentType): Icon
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon): Icon
    {
        $this->icon = $icon;
        return $this;
    }

    public function getDateUpdated(): ?DateTime
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(?DateTime $dateUpdated): Icon
    {
        $this->dateUpdated = $dateUpdated;
        return $this;
    }

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): Icon
    {
        $this->challenge = $challenge;
        return $this;
    }
}
