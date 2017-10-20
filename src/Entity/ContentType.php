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
use Zend\Form\Annotation;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Entity for the Country.
 *
 * @ORM\Table(name="contenttype")
 * @ORM\Entity(repositoryClass="General\Repository\ContentType")
 *
 * @category General
 */
class ContentType extends EntityAbstract implements ResourceInterface
{
    const TYPE_UNKNOWN = 0;
    const TYPE_PDF = 1;
    const TYPE_OFFICE_2007 = 16;
    const TYPE_EXCEL = 13;
    const TYPE_EXCEL_2007 = 19;
    const TYPE_EXCEL_MACRO = 143;
    /**
     * @ORM\Column(name="contenttype_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="description", type="string", unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-content-type-description-label","help-block":"txt-content-type-description-help-block"})
     * @var string
     */
    private $description;
    /**
     * @ORM\Column(name="contenttype", type="string", unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-content-type-label","help-block":"txt-content-type-help-block"})
     *
     * @var string
     */
    private $contentType;
    /**
     * @ORM\Column(name="extension", type="string", unique=true, nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-content-type-extension-label","help-block":"txt-content-type-extension-help-block"})
     * @var string
     */
    private $extension;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Logo", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Logo[]|Collections\ArrayCollection
     */
    private $projectLogo;
    /**
     * @ORM\Column(name="gifimage",  type="blob", nullable=true)
     * @Annotation\Exclude()
     * @var resource
     */
    private $image;
    /**
     * @ORM\OneToMany(targetEntity="Content\Entity\Image", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Content\Entity\Image[]|Collections\ArrayCollection
     */
    private $contentImage;
    /**
     * @ORM\OneToMany(targetEntity="Press\Entity\Article", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Content\Entity\Image[]|Collections\ArrayCollection
     */
    private $pressArticle;
    /**
     * @ORM\OneToMany(targetEntity="Program\Entity\Nda", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Program\Entity\Nda[]|Collections\ArrayCollection
     */
    private $programNna;
    /**
     * @ORM\OneToMany(targetEntity="Program\Entity\Doa", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Program\Entity\Doa[]|Collections\ArrayCollection
     */
    private $programDoa;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\Parent\Doa", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Organisation\Entity\Parent\Doa[]|Collections\ArrayCollection
     */
    private $parentDoa;
    /**
     * @ORM\OneToMany(targetEntity="Affiliation\Entity\Doa", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Affiliation\Entity\Doa[]|Collections\ArrayCollection
     */
    private $affiliationDoa;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Dnd", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Contact\Entity\Dnd[]|Collections\ArrayCollection
     */
    private $contactDnd;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Photo", cascade="persist", mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Contact\Entity\Photo[]|Collections\ArrayCollection
     */
    private $contactPhoto;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\Logo", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Organisation\Entity\Logo[]|Collections\ArrayCollection
     */
    private $organisationLogo;
    /**
     * @ORM\OneToMany(targetEntity="Publication\Entity\Publication", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Publication\Entity\Publication[]|Collections\ArrayCollection
     */
    private $publication;
    /**
     * @ORM\OneToMany(targetEntity="Event\Entity\Badge\Attachment", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Publication\Entity\Publication[]|Collections\ArrayCollection
     */
    private $badgeAttachment;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Result\Result", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Result\Result[]|Collections\ArrayCollection
     */
    private $result;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Workpackage\Document", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Workpackage\Document[]|Collections\ArrayCollection
     */
    private $workpackageDocument;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Poster\Poster", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Poster\Poster[]|Collections\ArrayCollection
     */
    private $poster;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Idea\Document", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Idea\Document[]|Collections\ArrayCollection
     */
    private $ideaDocument;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Idea\Image", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Idea\Image[]|Collections\ArrayCollection
     */
    private $ideaImage;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Description\Image", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Description\Image[]|Collections\ArrayCollection
     */
    private $projectDescriptionImage;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Report\Item", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Report\Item[]|Collections\ArrayCollection
     */
    private $projectReportItem;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Document\Document", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Document\Document[]|Collections\ArrayCollection
     */
    private $projectDocument;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Version\Document", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Version\Document[]|Collections\ArrayCollection
     */
    private $versionDocument;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Contract\Document", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Contract\Document[]|Collections\ArrayCollection
     */
    private $contractDocument;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Contract\VersionDocument", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Project\Entity\Contract\VersionDocument[]|Collections\ArrayCollection
     */
    private $contractVersionDocument;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\Challenge\Image", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \General\Entity\Challenge\Image[]|Collections\ArrayCollection
     */
    private $challengeImage;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\Challenge\Icon", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \General\Entity\Challenge\Icon[]|Collections\ArrayCollection
     */
    private $challengeIcon;
    /**
     * @ORM\OneToMany(targetEntity="Calendar\Entity\Document", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Calendar\Entity\Document[]|Collections\ArrayCollection
     */
    private $calendarDocument;
    /**
     * @ORM\OneToMany(targetEntity="Affiliation\Entity\Loi", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Affiliation\Entity\Loi[]|Collections\ArrayCollection
     */
    private $loi;
    /**
     * @ORM\OneToMany(targetEntity="Event\Entity\Meeting\Floorplan", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Event\Entity\Meeting\Floorplan[]|Collections\ArrayCollection
     */
    private $meetingFloorplan;
    /**
     * @ORM\OneToMany(targetEntity="Event\Entity\Exhibition\Floorplan", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Event\Entity\Exhibition\Floorplan[]|Collections\ArrayCollection
     */
    private $exhibitionFloorplan;
    /**
     * @ORM\OneToMany(targetEntity="Invoice\Entity\Reminder", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var \Invoice\Entity\Reminder[]|Collections\ArrayCollection()
     */
    private $reminder;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->projectLogo = new Collections\ArrayCollection();
        $this->contentImage = new Collections\ArrayCollection();
        $this->pressArticle = new Collections\ArrayCollection();
        $this->programNna = new Collections\ArrayCollection();
        $this->programDoa = new Collections\ArrayCollection();
        $this->parentDoa = new Collections\ArrayCollection();
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
        $this->projectReportItem = new Collections\ArrayCollection();
        $this->projectDocument = new Collections\ArrayCollection();
        $this->versionDocument = new Collections\ArrayCollection();
        $this->contractDocument = new Collections\ArrayCollection();
        $this->contractVersionDocument = new Collections\ArrayCollection();
        $this->challengeIcon = new Collections\ArrayCollection();
        $this->challengeImage = new Collections\ArrayCollection();
        $this->calendarDocument = new Collections\ArrayCollection();
        $this->loi = new Collections\ArrayCollection();
        $this->meetingFloorplan = new Collections\ArrayCollection();
        $this->exhibitionFloorplan = new Collections\ArrayCollection();
        $this->reminder = new Collections\ArrayCollection();
    }

    /**
     * Return the name of the content type.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->contentType;
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
     * @return ContentType
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     *
     * @return ContentType
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
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
     *
     * @return ContentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
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
     *
     * @return ContentType
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Logo[]
     */
    public function getProjectLogo()
    {
        return $this->projectLogo;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Logo[] $projectLogo
     *
     * @return ContentType
     */
    public function setProjectLogo($projectLogo)
    {
        $this->projectLogo = $projectLogo;

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
     * @param resource $image
     *
     * @return ContentType
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return \Content\Entity\Image[]|Collections\ArrayCollection
     */
    public function getContentImage()
    {
        return $this->contentImage;
    }

    /**
     * @param \Content\Entity\Image[]|Collections\ArrayCollection $contentImage
     *
     * @return ContentType
     */
    public function setContentImage($contentImage)
    {
        $this->contentImage = $contentImage;

        return $this;
    }

    /**
     * @return \Content\Entity\Image[]|Collections\ArrayCollection
     */
    public function getPressArticle()
    {
        return $this->pressArticle;
    }

    /**
     * @param \Content\Entity\Image[]|Collections\ArrayCollection $pressArticle
     *
     * @return ContentType
     */
    public function setPressArticle($pressArticle)
    {
        $this->pressArticle = $pressArticle;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Program\Entity\Nda[]
     */
    public function getProgramNna()
    {
        return $this->programNna;
    }

    /**
     * @param Collections\ArrayCollection|\Program\Entity\Nda[] $programNna
     *
     * @return ContentType
     */
    public function setProgramNna($programNna)
    {
        $this->programNna = $programNna;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Program\Entity\Doa[]
     */
    public function getProgramDoa()
    {
        return $this->programDoa;
    }

    /**
     * @param Collections\ArrayCollection|\Program\Entity\Doa[] $programDoa
     *
     * @return ContentType
     */
    public function setProgramDoa($programDoa)
    {
        $this->programDoa = $programDoa;

        return $this;
    }

    /**
     * @return \Affiliation\Entity\Doa[]|Collections\ArrayCollection
     */
    public function getAffiliationDoa()
    {
        return $this->affiliationDoa;
    }

    /**
     * @param \Affiliation\Entity\Doa[]|Collections\ArrayCollection $affiliationDoa
     *
     * @return ContentType
     */
    public function setAffiliationDoa($affiliationDoa)
    {
        $this->affiliationDoa = $affiliationDoa;

        return $this;
    }

    /**
     * @return \Contact\Entity\Dnd[]|Collections\ArrayCollection
     */
    public function getContactDnd()
    {
        return $this->contactDnd;
    }

    /**
     * @param \Contact\Entity\Dnd[]|Collections\ArrayCollection $contactDnd
     *
     * @return ContentType
     */
    public function setContactDnd($contactDnd)
    {
        $this->contactDnd = $contactDnd;

        return $this;
    }

    /**
     * @return \Contact\Entity\Photo[]|Collections\ArrayCollection
     */
    public function getContactPhoto()
    {
        return $this->contactPhoto;
    }

    /**
     * @param \Contact\Entity\Photo[]|Collections\ArrayCollection $contactPhoto
     *
     * @return ContentType
     */
    public function setContactPhoto($contactPhoto)
    {
        $this->contactPhoto = $contactPhoto;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Organisation\Entity\Logo[]
     */
    public function getOrganisationLogo()
    {
        return $this->organisationLogo;
    }

    /**
     * @param Collections\ArrayCollection|\Organisation\Entity\Logo[] $organisationLogo
     *
     * @return ContentType
     */
    public function setOrganisationLogo($organisationLogo)
    {
        $this->organisationLogo = $organisationLogo;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Publication\Entity\Publication[]
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param Collections\ArrayCollection|\Publication\Entity\Publication[] $publication
     *
     * @return ContentType
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Publication\Entity\Publication[]
     */
    public function getBadgeAttachment()
    {
        return $this->badgeAttachment;
    }

    /**
     * @param Collections\ArrayCollection|\Publication\Entity\Publication[] $badgeAttachment
     *
     * @return ContentType
     */
    public function setBadgeAttachment($badgeAttachment)
    {
        $this->badgeAttachment = $badgeAttachment;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Result\Result[]
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Result\Result[] $result
     *
     * @return ContentType
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Workpackage\Document[]
     */
    public function getWorkpackageDocument()
    {
        return $this->workpackageDocument;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Workpackage\Document[] $workpackageDocument
     *
     * @return ContentType
     */
    public function setWorkpackageDocument($workpackageDocument)
    {
        $this->workpackageDocument = $workpackageDocument;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Poster\Poster[]
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Poster\Poster[] $poster
     *
     * @return ContentType
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Idea\Document[]
     */
    public function getIdeaDocument()
    {
        return $this->ideaDocument;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Idea\Document[] $ideaDocument
     *
     * @return ContentType
     */
    public function setIdeaDocument($ideaDocument)
    {
        $this->ideaDocument = $ideaDocument;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Idea\Image[]
     */
    public function getIdeaImage()
    {
        return $this->ideaImage;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Idea\Image[] $ideaImage
     *
     * @return ContentType
     */
    public function setIdeaImage($ideaImage)
    {
        $this->ideaImage = $ideaImage;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Description\Image[]
     */
    public function getProjectDescriptionImage()
    {
        return $this->projectDescriptionImage;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Description\Image[] $projectDescriptionImage
     *
     * @return ContentType
     */
    public function setProjectDescriptionImage($projectDescriptionImage)
    {
        $this->projectDescriptionImage = $projectDescriptionImage;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Report\Item[]
     */
    public function getProjectReportItem()
    {
        return $this->projectReportItem;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Report\Item[] $projectReportItem
     *
     * @return ContentType
     */
    public function setProjectReportItem($projectReportItem)
    {
        $this->projectReportItem = $projectReportItem;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Document\Document[]
     */
    public function getProjectDocument()
    {
        return $this->projectDocument;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Document\Document[] $projectDocument
     *
     * @return ContentType
     */
    public function setProjectDocument($projectDocument)
    {
        $this->projectDocument = $projectDocument;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Version\Document[]
     */
    public function getVersionDocument()
    {
        return $this->versionDocument;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Version\Document[] $versionDocument
     *
     * @return ContentType
     */
    public function setVersionDocument($versionDocument)
    {
        $this->versionDocument = $versionDocument;

        return $this;
    }

    /**
     * @return \Calendar\Entity\Document[]|Collections\ArrayCollection
     */
    public function getCalendarDocument()
    {
        return $this->calendarDocument;
    }

    /**
     * @param \Calendar\Entity\Document[]|Collections\ArrayCollection $calendarDocument
     *
     * @return ContentType
     */
    public function setCalendarDocument($calendarDocument)
    {
        $this->calendarDocument = $calendarDocument;

        return $this;
    }

    /**
     * @return \Affiliation\Entity\Loi[]|Collections\ArrayCollection
     */
    public function getLoi()
    {
        return $this->loi;
    }

    /**
     * @param \Affiliation\Entity\Loi[]|Collections\ArrayCollection $loi
     *
     * @return ContentType
     */
    public function setLoi($loi)
    {
        $this->loi = $loi;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Event\Entity\Meeting\Floorplan[]
     */
    public function getMeetingFloorplan()
    {
        return $this->meetingFloorplan;
    }

    /**
     * @param Collections\ArrayCollection|\Event\Entity\Meeting\Floorplan[] $meetingFloorplan
     *
     * @return ContentType
     */
    public function setMeetingFloorplan($meetingFloorplan)
    {
        $this->meetingFloorplan = $meetingFloorplan;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Event\Entity\Exhibition\Floorplan[]
     */
    public function getExhibitionFloorplan()
    {
        return $this->exhibitionFloorplan;
    }

    /**
     * @param Collections\ArrayCollection|\Event\Entity\Exhibition\Floorplan[] $exhibitionFloorplan
     *
     * @return ContentType
     */
    public function setExhibitionFloorplan($exhibitionFloorplan)
    {
        $this->exhibitionFloorplan = $exhibitionFloorplan;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Invoice\Entity\Reminder[]
     */
    public function getReminder()
    {
        return $this->reminder;
    }

    /**
     * @param Collections\ArrayCollection|\Invoice\Entity\Reminder[] $reminder
     *
     * @return ContentType
     */
    public function setReminder($reminder)
    {
        $this->reminder = $reminder;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Organisation\Entity\Parent\Doa[]
     */
    public function getParentDoa()
    {
        return $this->parentDoa;
    }

    /**
     * @param Collections\ArrayCollection|\Organisation\Entity\Parent\Doa[] $parentDoa
     *
     * @return ContentType
     */
    public function setParentDoa($parentDoa)
    {
        $this->parentDoa = $parentDoa;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Contract\Document[]
     */
    public function getContractDocument()
    {
        return $this->contractDocument;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Contract\Document[] $contractDocument
     * @return ContentType
     */
    public function setContractDocument($contractDocument): ContentType
    {
        $this->contractDocument = $contractDocument;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Contract\VersionDocument[]
     */
    public function getContractVersionDocument()
    {
        return $this->contractVersionDocument;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Contract\VersionDocument[] $contractVersionDocument
     * @return ContentType
     */
    public function setContractVersionDocument($contractVersionDocument): ContentType
    {
        $this->contractVersionDocument = $contractVersionDocument;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|Challenge\Image[]
     */
    public function getChallengeImage()
    {
        return $this->challengeImage;
    }

    /**
     * @param Collections\ArrayCollection|Challenge\Image[] $challengeImage
     * @return ContentType
     */
    public function setChallengeImage($challengeImage): ContentType
    {
        $this->challengeImage = $challengeImage;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|Challenge\Icon[]
     */
    public function getChallengeIcon()
    {
        return $this->challengeIcon;
    }

    /**
     * @param Collections\ArrayCollection|Challenge\Icon[] $challengeIcon
     * @return ContentType
     */
    public function setChallengeIcon($challengeIcon): ContentType
    {
        $this->challengeIcon = $challengeIcon;

        return $this;
    }
}
