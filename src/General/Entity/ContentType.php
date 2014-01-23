<?php
/**
 * ITEA copyright message placeholder
 *
 * @category    General
 * @package     Entity
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
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
     * @var resource
     */
    private $image;
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
     * @ORM\OneToMany(targetEntity="Contact\Entity\Photo", cascade="persist", mappedBy="contentType")
     * @var \Contact\Entity\Photo[]
     */
    private $contactPhoto;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\Logo", cascade={"persist"}, mappedBy="contentType")
     * @var \Organisation\Entity\Logo[]
     */
    private $organisationLogo;
    /**
     * @ORM\OneToMany(targetEntity="Publication\Entity\Publication", cascade={"persist"}, mappedBy="contentType")
     * @var \Publication\Entity\Publication[]
     */
    private $publication;
    /**
     * @ORM\OneToMany(targetEntity="Event\Entity\Badge\Attachment", cascade={"persist"}, mappedBy="contentType")
     * @var \Publication\Entity\Publication[]
     */
    private $badgeAttachment;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Result", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\Result[]
     */
    private $result;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\WorkpackageDocument", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\WorkpackageDocument[]
     */
    private $workpackageDocument;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Poster", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\WorkpackageDocument[]
     */
    private $poster;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Idea\Document", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\Idea\Document[]
     */
    private $ideaDocument;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Idea\Image", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\Idea\Image[]
     */
    private $ideaImage;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Description\Image", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\Idea\Image[]
     */
    private $projectDescriptionImage;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Document\Document", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\Document\Document[]
     */
    private $projectDocument;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\VersionDocument", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\VersionDocument[]
     */
    private $versionDocument;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->projectLogo             = new Collections\ArrayCollection();
        $this->contentImage            = new Collections\ArrayCollection();
        $this->pressArticle            = new Collections\ArrayCollection();
        $this->programNna              = new Collections\ArrayCollection();
        $this->programDoa              = new Collections\ArrayCollection();
        $this->programDnd              = new Collections\ArrayCollection();
        $this->organisationLogo        = new Collections\ArrayCollection();
        $this->contactDnd              = new Collections\ArrayCollection();
        $this->contactPhoto            = new Collections\ArrayCollection();
        $this->publication             = new Collections\ArrayCollection();
        $this->badgeAttachment         = new Collections\ArrayCollection();
        $this->result                  = new Collections\ArrayCollection();
        $this->workpackageDocument     = new Collections\ArrayCollection();
        $this->poster                  = new Collections\ArrayCollection();
        $this->ideaDocument            = new Collections\ArrayCollection();
        $this->ideaImage               = new Collections\ArrayCollection();
        $this->projectDescriptionImage = new Collections\ArrayCollection();
        $this->projectDocument         = new Collections\ArrayCollection();
        $this->versionDocument         = new Collections\ArrayCollection();
    }

    /**
     * Although an alternative does not have a clear hash, we can create one based on the id;
     *
     * @return string
     */
    public function getHash()
    {
        return sha1($this->id . $this->contentType . $this->extension);
    }

    /**
     * Return the name of the content type
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->contentType;
    }

    /**
     * Get the corresponding fileName of a file if it was cached
     * Use a dash (-) to make the distinction between the format to avoid the need of an extra folder
     *
     * @return string
     * @todo: make the location variable (via the serviceManager?)
     */
    public function getCacheFileName()
    {

        $cacheDir = __DIR__ . '/../../../../../../public' . DIRECTORY_SEPARATOR . 'assets' .
            DIRECTORY_SEPARATOR . DEBRANOVA_HOST . DIRECTORY_SEPARATOR . 'content-type-icon';

        return $cacheDir . DIRECTORY_SEPARATOR
        . $this->getHash() . '.gif';
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
     * @param resource $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return resource
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

    /**
     * @param \Organisation\Entity\Logo[] $organisationLogo
     */
    public function setOrganisationLogo($organisationLogo)
    {
        $this->organisationLogo = $organisationLogo;
    }

    /**
     * @return \Organisation\Entity\Logo[]
     */
    public function getOrganisationLogo()
    {
        return $this->organisationLogo;
    }

    /**
     * @param \Publication\Entity\Publication[] $publication
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
    }

    /**
     * @return \Publication\Entity\Publication[]
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param \Contact\Entity\Photo[] $contactPhoto
     */
    public function setContactPhoto($contactPhoto)
    {
        $this->contactPhoto = $contactPhoto;
    }

    /**
     * @return \Contact\Entity\Photo[]
     */
    public function getContactPhoto()
    {
        return $this->contactPhoto;
    }

    /**
     * @param \Publication\Entity\Publication[] $badgeAttachment
     */
    public function setBadgeAttachment($badgeAttachment)
    {
        $this->badgeAttachment = $badgeAttachment;
    }

    /**
     * @return \Publication\Entity\Publication[]
     */
    public function getBadgeAttachment()
    {
        return $this->badgeAttachment;
    }

    /**
     * @param \Project\Entity\Result[] $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return \Project\Entity\Result[]
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param \Project\Entity\WorkpackageDocument[] $workpackageDocument
     */
    public function setWorkpackageDocument($workpackageDocument)
    {
        $this->workpackageDocument = $workpackageDocument;
    }

    /**
     * @return \Project\Entity\WorkpackageDocument[]
     */
    public function getWorkpackageDocument()
    {
        return $this->workpackageDocument;
    }

    /**
     * @param \Project\Entity\WorkpackageDocument[] $poster
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;
    }

    /**
     * @return \Project\Entity\WorkpackageDocument[]
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param \Project\Entity\Idea\Document[] $ideaDocument
     */
    public function setIdeaDocument($ideaDocument)
    {
        $this->ideaDocument = $ideaDocument;
    }

    /**
     * @return \Project\Entity\Idea\Document[]
     */
    public function getIdeaDocument()
    {
        return $this->ideaDocument;
    }

    /**
     * @param \Project\Entity\Idea\Image[] $ideaImage
     */
    public function setIdeaImage($ideaImage)
    {
        $this->ideaImage = $ideaImage;
    }

    /**
     * @return \Project\Entity\Idea\Image[]
     */
    public function getIdeaImage()
    {
        return $this->ideaImage;
    }

    /**
     * @param \Project\Entity\Idea\Image[] $projectDescriptionImage
     */
    public function setProjectDescriptionImage($projectDescriptionImage)
    {
        $this->projectDescriptionImage = $projectDescriptionImage;
    }

    /**
     * @return \Project\Entity\Idea\Image[]
     */
    public function getProjectDescriptionImage()
    {
        return $this->projectDescriptionImage;
    }

    /**
     * @param \Project\Entity\Document\Document[] $projectDocument
     */
    public function setProjectDocument($projectDocument)
    {
        $this->projectDocument = $projectDocument;
    }

    /**
     * @return \Project\Entity\Document\Document[]
     */
    public function getProjectDocument()
    {
        return $this->projectDocument;
    }

    /**
     * @param \Project\Entity\VersionDocument[] $versionDocument
     */
    public function setVersionDocument($versionDocument)
    {
        $this->versionDocument = $versionDocument;
    }

    /**
     * @return \Project\Entity\VersionDocument[]
     */
    public function getVersionDocument()
    {
        return $this->versionDocument;
    }
}
