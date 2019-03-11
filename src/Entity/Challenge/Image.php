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

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use General\Entity\AbstractEntity;

/**
 * ProjectImage.
 *
 * @ORM\Table(name="challenge_image")
 * @ORM\Entity
 */
class Image extends AbstractEntity
{
    /**
     * @ORM\Column(name="image_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\ContentType", cascade={"persist"}, inversedBy="challengeImage")
     * @ORM\JoinColumn(name="contenttype_id", referencedColumnName="contenttype_id", nullable=false)
     *
     * @var \General\Entity\ContentType
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
     * @var \DateTime
     */
    private $dateUpdated;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge", cascade={"persist"}, inversedBy="image")
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="challenge_id", nullable=false)
     *
     * @var \General\Entity\Challenge
     */
    private $challenge;

    public function __get($property)
    {
        return $this->$property;
    }

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
     *
     * @return Image
     */
    public function setId(int $id): Image
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return \General\Entity\ContentType
     */
    public function getContentType(): \General\Entity\ContentType
    {
        return $this->contentType;
    }

    /**
     * @param \General\Entity\ContentType $contentType
     *
     * @return Image
     */
    public function setContentType(\General\Entity\ContentType $contentType): Image
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @return resource
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return Image
     */
    public function setImage(string $image): Image
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateUpdated(): \DateTime
    {
        return $this->dateUpdated;
    }

    /**
     * @param \DateTime $dateUpdated
     *
     * @return Image
     */
    public function setDateUpdated(\DateTime $dateUpdated): Image
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * @return \General\Entity\Challenge
     */
    public function getChallenge(): \General\Entity\Challenge
    {
        return $this->challenge;
    }

    /**
     * @param \General\Entity\Challenge $challenge
     *
     * @return Image
     */
    public function setChallenge(\General\Entity\Challenge $challenge): Image
    {
        $this->challenge = $challenge;

        return $this;
    }
}
