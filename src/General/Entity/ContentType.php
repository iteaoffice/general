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
 * Entity for the Country.
 *
 * @ORM\Table(name="contenttype")
 * @ORM\Entity
 *
 * @category    General
 * @package     Entity
 */
class ContentType
{
    /**
     * @ORM\Column(name="contenttype_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="description", type="string", unique=true)
     * @var string
     */
    private $description;
    /**
     * @ORM\Column(name="contenttype", type="string", unique=true)
     * @var string
     */
    private $contentType;
    /**
     * @ORM\Column(name="extension", type="string", unique=true)
     * @var string
     */
    private $extension;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Logo", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\Logo[]
     */
    private $projectLogo;
    /**
     * @ORM\Column(name="gifimage",  type="blob")
     * @var string
     */
    private $image;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->projectLogo = new Collections\ArrayCollection();
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
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
     * @param \Project\Entity\Logo[] $projectLogo
     */
    public function setProjectLogo($projectLogo)
    {
        $this->projectLogo = $projectLogo;
    }

    /**
     * @return \Project\Entity\Logo[]
     */
    public function getProjectLogo()
    {
        return $this->projectLogo;
    }
}
