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
    const TYPE_OFFICE_2007 = 16;
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
     * @ORM\OneToMany(targetEntity="Program\Entity\Doa", cascade={"persist"}, mappedBy="contentType")
     * @var \Program\Entity\Doa[]
     */
    private $programDoa;
    /**
     * @ORM\OneToMany(targetEntity="Affiliation\Entity\Doa", cascade={"persist"}, mappedBy="contentType")
     * @var \Affiliation\Entity\Doa[]
     */
    private $affiliationDoa;
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
     * @ORM\OneToMany(targetEntity="Project\Entity\Result\Result", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\Result\Result[]
     */
    private $result;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Workpackage\Document", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\Workpackage\Document[]
     */
    private $workpackageDocument;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Poster\Poster", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\Poster\Poster[]
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
     * @ORM\OneToMany(targetEntity="Project\Entity\Version\Document", cascade={"persist"}, mappedBy="contentType")
     * @var \Project\Entity\Version\Document[]
     */
    private $versionDocument;
    /**
     * @ORM\OneToMany(targetEntity="Calendar\Entity\Document", cascade={"persist"}, mappedBy="contentType")
     * @var \Calendar\Entity\Document[]
     */
    private $calendarDocument;
    /**
     * @ORM\OneToMany(targetEntity="Affiliation\Entity\Loi", cascade={"persist"}, mappedBy="contentType")
     * @var \Affiliation\Entity\Loi[]
     */
    private $loi;
    /**
     * @ORM\OneToMany(targetEntity="Event\Entity\Meeting\Floorplan", cascade={"persist"}, mappedBy="contentType")
     * @var \Event\Entity\Meeting\Floorplan[]
     */
    private $meetingFloorplan;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->projectLogo = new Collections\ArrayCollection();
        $this->contentImage = new Collections\ArrayCollection();
        $this->pressArticle = new Collections\ArrayCollection();
        $this->programNna = new Collections\ArrayCollection();
        $this->programDoa = new Collections\ArrayCollection();
        $this->organisationLogo = new Collections\ArrayCollection();
        $this->contactDnd = new Collections\ArrayCollection();
        $this->contactPhoto = new Collections\ArrayCollection();
        $this->publication = new Collections\ArrayCollection();
        $this->badgeAttachment = new Collections\ArrayCollection();
        $this->result = new Collections\ArrayCollection();
        $this->workpackageDocument = new Collections\ArrayCollection();
        $this->poster = new Collections\ArrayCollection();
        $this->ideaDocument = new Collections\ArrayCollection();
        $this->ideaImage = new Collections\ArrayCollection();
        $this->projectDescriptionImage = new Collections\ArrayCollection();
        $this->projectDocument = new Collections\ArrayCollection();
        $this->versionDocument = new Collections\ArrayCollection();
        $this->calendarDocument = new Collections\ArrayCollection();
        $this->loi = new Collections\ArrayCollection();
        $this->meetingFloorplan = new Collections\ArrayCollection();
    }

    /**
     * Return the name of the content type
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->contentType;
    }

    /**
     * Get the corresponding fileName of a file if it was cached
     * Use a dash (-) to make the distinction between the format to avoid the need of an extra folder
     *
     * @return string
     */
    public function getCacheFileName()
    {
        $cacheDir = __DIR__ . '/../../../../../../public' . DIRECTORY_SEPARATOR . 'assets' .
            DIRECTORY_SEPARATOR .
            (defined("DEBRANOVA_HOST") ? DEBRANOVA_HOST : 'test') .
            DIRECTORY_SEPARATOR . 'content-type-icon';

        return $cacheDir . DIRECTORY_SEPARATOR
        . $this->getHash() . '.gif';
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
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
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
    public function getDescription()
    {
        return $this->description;
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
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
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
     * @return resource
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param resource $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return \Content\Entity\Image[]
     */
    public function getContentImage()
    {
        return $this->contentImage;
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
    public function getPressArticle()
    {
        return $this->pressArticle;
    }

    /**
     * @param \Content\Entity\Image[] $pressArticle
     */
    public function setPressArticle($pressArticle)
    {
        $this->pressArticle = $pressArticle;
    }

    /**
     * @return \Project\Entity\Logo[]
     */
    public function getProjectLogo()
    {
        return $this->projectLogo;
    }

    /**
     * @param \Project\Entity\Logo[] $projectLogo
     */
    public function setProjectLogo($projectLogo)
    {
        $this->projectLogo = $projectLogo;
    }

    /**
     * @return \Program\Entity\Nda[]
     */
    public function getProgramNna()
    {
        return $this->programNna;
    }

    /**
     * @param \Program\Entity\Nda[] $programNna
     */
    public function setProgramNna($programNna)
    {
        $this->programNna = $programNna;
    }

    /**
     * @return \Program\Entity\Doa[]
     */
    public function getProgramDoa()
    {
        return $this->programDoa;
    }

    /**
     * @param \Program\Entity\Doa[] $programDoa
     */
    public function setProgramDoa($programDoa)
    {
        $this->programDoa = $programDoa;
    }

    /**
     * @return \Affiliation\Entity\Doa[]
     */
    public function getAffiliationDoa()
    {
        return $this->affiliationDoa;
    }

    /**
     * @param \Affiliation\Entity\Doa[] $affiliationDoa
     */
    public function setAffiliationDoa($affiliationDoa)
    {
        $this->affiliationDoa = $affiliationDoa;
    }

    /**
     * @return \Contact\Entity\Dnd[]
     */
    public function getContactDnd()
    {
        return $this->contactDnd;
    }

    /**
     * @param \Contact\Entity\Dnd[] $contactDnd
     */
    public function setContactDnd($contactDnd)
    {
        $this->contactDnd = $contactDnd;
    }

    /**
     * @return \Organisation\Entity\Logo[]
     */
    public function getOrganisationLogo()
    {
        return $this->organisationLogo;
    }

    /**
     * @param \Organisation\Entity\Logo[] $organisationLogo
     */
    public function setOrganisationLogo($organisationLogo)
    {
        $this->organisationLogo = $organisationLogo;
    }

    /**
     * @return \Publication\Entity\Publication[]
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param \Publication\Entity\Publication[] $publication
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
    }

    /**
     * @return \Contact\Entity\Photo[]
     */
    public function getContactPhoto()
    {
        return $this->contactPhoto;
    }

    /**
     * @param \Contact\Entity\Photo[] $contactPhoto
     */
    public function setContactPhoto($contactPhoto)
    {
        $this->contactPhoto = $contactPhoto;
    }

    /**
     * @return \Publication\Entity\Publication[]
     */
    public function getBadgeAttachment()
    {
        return $this->badgeAttachment;
    }

    /**
     * @param \Publication\Entity\Publication[] $badgeAttachment
     */
    public function setBadgeAttachment($badgeAttachment)
    {
        $this->badgeAttachment = $badgeAttachment;
    }

    /**
     * @return \Project\Entity\Result\Result[]
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param \Project\Entity\Result\Result[] $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return \Project\Entity\Workpackage\Document[]
     */
    public function getWorkpackageDocument()
    {
        return $this->workpackageDocument;
    }

    /**
     * @param \Project\Entity\Workpackage\Document[] $workpackageDocument
     */
    public function setWorkpackageDocument($workpackageDocument)
    {
        $this->workpackageDocument = $workpackageDocument;
    }

    /**
     * @return \Project\Entity\Poster\Poster[]
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param \Project\Entity\Poster\Poster[] $poster
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;
    }

    /**
     * @return \Project\Entity\Idea\Document[]
     */
    public function getIdeaDocument()
    {
        return $this->ideaDocument;
    }

    /**
     * @param \Project\Entity\Idea\Document[] $ideaDocument
     */
    public function setIdeaDocument($ideaDocument)
    {
        $this->ideaDocument = $ideaDocument;
    }

    /**
     * @return \Project\Entity\Idea\Image[]
     */
    public function getIdeaImage()
    {
        return $this->ideaImage;
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
    public function getProjectDescriptionImage()
    {
        return $this->projectDescriptionImage;
    }

    /**
     * @param \Project\Entity\Idea\Image[] $projectDescriptionImage
     */
    public function setProjectDescriptionImage($projectDescriptionImage)
    {
        $this->projectDescriptionImage = $projectDescriptionImage;
    }

    /**
     * @return \Project\Entity\Document\Document[]
     */
    public function getProjectDocument()
    {
        return $this->projectDocument;
    }

    /**
     * @param \Project\Entity\Document\Document[] $projectDocument
     */
    public function setProjectDocument($projectDocument)
    {
        $this->projectDocument = $projectDocument;
    }

    /**
     * @return \Project\Entity\Version\Document[]
     */
    public function getVersionDocument()
    {
        return $this->versionDocument;
    }

    /**
     * @param \Project\Entity\Version\Document[] $versionDocument
     */
    public function setVersionDocument($versionDocument)
    {
        $this->versionDocument = $versionDocument;
    }

    /**
     * @return \Project\Entity\Version\Document[]
     */
    public function getCalendarDocument()
    {
        return $this->calendarDocument;
    }

    /**
     * @param \Project\Entity\Version\Document[] $calendarDocument
     */
    public function setCalendarDocument($calendarDocument)
    {
        $this->calendarDocument = $calendarDocument;
    }

    /**
     * @return \Affiliation\Entity\Loi[]
     */
    public function getLoi()
    {
        return $this->loi;
    }

    /**
     * @param \Affiliation\Entity\Loi[] $loi
     */
    public function setLoi($loi)
    {
        $this->loi = $loi;
    }

    /**
     * @return \Event\Entity\Meeting\Floorplan[]
     */
    public function getMeetingFloorplan()
    {
        return $this->meetingFloorplan;
    }

    /**
     * @param \Event\Entity\Meeting\Floorplan[] $meetingFloorplan
     */
    public function setMeetingFloorplan($meetingFloorplan)
    {
        $this->meetingFloorplan = $meetingFloorplan;
    }
}
