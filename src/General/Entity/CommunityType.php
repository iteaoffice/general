<?php
/**
 * ITEA copyright message placeholder
 *
 * @category    General
 * @package     Entity
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 ITEA
 */
namespace General\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for the Community Type.
 *
 * @ORM\Table(name="contact_community_type")
 * @ORM\Entity
 *
 * @category    General
 * @package     Entity
 */
class CommunityType
{
    /**
     * @ORM\Column(name="type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="type", type="string", unique=true)
     * @var string
     */
    private $type;
    /**
     * @ORM\Column(name="regularexpression", type="string", unique=true)
     * @var string
     */
    private $regularExpression;
    /**
     * @ORM\Column(name="link", type="string", unique=true)
     * @var string
     */
    private $link;
    /**
     * @ORM\Column(name="image", type="string", unique=true)
     * @var string
     */
    private $image;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Community", cascade={"persist"}, mappedBy="type")
     * @var \Contact\Entity\Community[]
     */
    private $community;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->community = new Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->type;
    }


    /**
     * @param \Contact\Entity\Community[] $community
     */
    public function setCommunity($community)
    {
        $this->community = $community;
    }

    /**
     * @return \Contact\Entity\Community[]
     */
    public function getCommunity()
    {
        return $this->community;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $regularExpression
     */
    public function setRegularExpression($regularExpression)
    {
        $this->regularExpression = $regularExpression;
    }

    /**
     * @return string
     */
    public function getRegularExpression()
    {
        return $this->regularExpression;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
