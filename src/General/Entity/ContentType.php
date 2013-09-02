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
     * @ORM\OneToMany(targetEntity="Content\Entity\Image", cascade={"persist"}, mappedBy="contentType")
     * @var \Content\Entity\Image[]
     */
    private $contentImage;
    /**
     * @ORM\OneToMany(targetEntity="Press\Entity\Article", cascade={"persist"}, mappedBy="contentType")
     * @var \Content\Entity\Image[]
     */
    private $pressArticle;
    /**
     * @ORM\OneToMany(targetEntity="Program\Entity\Nda", cascade={"persist"}, mappedBy="contentType")
     * @var \Program\Entity\Nda[]
     */
    private $programNna;
    /**
     * @ORM\OneToMany(targetEntity="Program\Entity\ProgramDoa", cascade={"persist"}, mappedBy="contentType")
     * @var \Program\Entity\ProgramDoa[]
     */
    private $programDoa;
    /**
     * @ORM\OneToMany(targetEntity="Program\Entity\Dnd", cascade={"persist"}, mappedBy="contentType")
     * @var \Program\Entity\Dnd[]
     */
    private $programDnd;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Dnd", cascade={"persist"}, mappedBy="contentType")
     * @var \Contact\Entity\Dnd[]
     */
    private $contactDnd;
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
        $this->projectLogo  = new Collections\ArrayCollection();
        $this->contentImage = new Collections\ArrayCollection();
        $this->pressArticle = new Collections\ArrayCollection();
        $this->programNna   = new Collections\ArrayCollection();
        $this->programDoa   = new Collections\ArrayCollection();
        $this->programDnd   = new Collections\ArrayCollection();
        $this->contactDnd   = new Collections\ArrayCollection();
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
     * @param \Content\Entity\Image[] $contentImage
     */
    public function setContentImage($contentImage)
    {
        $this->contentImage = $contentImage;
    }

    /**
     * @return \Content\Entity\Image[]
     */
    public function getContentImage()
    {
        return $this->contentImage;
    }

    /**
     * @param \Content\Entity\Image[] $pressArticle
     */
    public function setPressArticle($pressArticle)
    {
        $this->pressArticle = $pressArticle;
    }

    /**
     * @return \Content\Entity\Image[]
     */
    public function getPressArticle()
    {
        return $this->pressArticle;
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

    /**
     * @param \Program\Entity\Nda[] $programNna
     */
    public function setProgramNna($programNna)
    {
        $this->programNna = $programNna;
    }

    /**
     * @return \Program\Entity\Nda[]
     */
    public function getProgramNna()
    {
        return $this->programNna;
    }

    /**
     * @param \Program\Entity\Dnd[] $programDnd
     */
    public function setProgramDnd($programDnd)
    {
        $this->programDnd = $programDnd;
    }

    /**
     * @return \Program\Entity\Dnd[]
     */
    public function getProgramDnd()
    {
        return $this->programDnd;
    }

    /**
     * @param \Program\Entity\ProgramDoa[] $programDoa
     */
    public function setProgramDoa($programDoa)
    {
        $this->programDoa = $programDoa;
    }

    /**
     * @return \Program\Entity\ProgramDoa[]
     */
    public function getProgramDoa()
    {
        return $this->programDoa;
    }

    /**
     * @param \Contact\Entity\Dnd[] $contactDnd
     */
    public function setContactDnd($contactDnd)
    {
        $this->contactDnd = $contactDnd;
    }

    /**
     * @return \Contact\Entity\Dnd[]
     */
    public function getContactDnd()
    {
        return $this->contactDnd;
    }
}
