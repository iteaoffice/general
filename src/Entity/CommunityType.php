<?php
/**
 * ITEA copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for the Community Type.
 *
 * @ORM\Table(name="contact_community_type")
 * @ORM\Entity
 *
 * @category General
 */
class CommunityType extends EntityAbstract
{
    /**
     * @ORM\Column(name="type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="type", type="string", unique=true)
     *
     * @var string
     */
    private $type;
    /**
     * @ORM\Column(name="regularexpression", type="string", unique=true)
     *
     * @var string
     */
    private $regularExpression;
    /**
     * @ORM\Column(name="link", type="string", unique=true)
     *
     * @var string
     */
    private $link;
    /**
     * @ORM\Column(name="image", type="string", unique=true)
     *
     * @var string
     */
    private $image;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Community", cascade={"persist"}, mappedBy="type")
     *
     * @var \Contact\Entity\Community[]
     */
    private $community;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->community = new Collections\ArrayCollection();
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
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->type;
    }

    /**
     * @return \Contact\Entity\Community[]
     */
    public function getCommunity()
    {
        return $this->community;
    }

    /**
     * @param \Contact\Entity\Community[] $community
     */
    public function setCommunity($community)
    {
        $this->community = $community;
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
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
    public function getLink()
    {
        return $this->link;
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
    public function getRegularExpression()
    {
        return $this->regularExpression;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
