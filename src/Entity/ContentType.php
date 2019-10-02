<?php
/**
 * ITEA copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Affiliation\Entity\Loi;
use Contact\Entity\Dnd;
use Contact\Entity\Photo;
use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Event\Entity\Exhibition\Floorplan;
use General\Entity\Challenge\Icon;
use Invoice\Entity\Reminder;
use Organisation\Entity\Logo;
use Organisation\Entity\Parent\Doa;
use Program\Entity\Nda;
use Project\Entity\Contract\Document;
use Project\Entity\Contract\VersionDocument;
use Project\Entity\Idea\Image;
use Project\Entity\Pca;
use Project\Entity\Poster\Poster;
use Project\Entity\Report\Item;
use Project\Entity\Result\Result;
use Publication\Entity\Publication;
use Zend\Form\Annotation;

/**
 * @ORM\Table(name="contenttype")
 * @ORM\Entity(repositoryClass="General\Repository\ContentType")
 */
class ContentType extends AbstractEntity
{
    public const TYPE_UNKNOWN = 0;
    public const TYPE_PDF = 1;
    public const TYPE_OFFICE_2007 = 16;
    public const TYPE_EXCEL = 13;
    public const TYPE_EXCEL_2007 = 19;
    public const TYPE_EXCEL_MACRO = 143;

    /**
     * @ORM\Column(name="contenttype_id", type="integer", options={"unsigned":true})
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
     * @ORM\Column(name="extension", type="string", nullable=true)
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
     * @var Nda[]|Collections\ArrayCollection
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
     * @var Doa[]|Collections\ArrayCollection
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
     * @var Dnd[]|Collections\ArrayCollection
     */
    private $contactDnd;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Photo", cascade="persist", mappedBy="contentType")
     * @Annotation\Exclude()
     * @var Photo[]|Collections\ArrayCollection
     */
    private $contactPhoto;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\Logo", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var Logo[]|Collections\ArrayCollection
     */
    private $organisationLogo;
    /**
     * @ORM\OneToMany(targetEntity="Publication\Entity\Publication", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var Publication[]|Collections\ArrayCollection
     */
    private $publication;
    /**
     * @ORM\OneToMany(targetEntity="Event\Entity\Badge\Attachment", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var Publication[]|Collections\ArrayCollection
     */
    private $badgeAttachment;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Result\Result", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var Result[]|Collections\ArrayCollection
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
     * @var Poster[]|Collections\ArrayCollection
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
     * @var Image[]|Collections\ArrayCollection
     */
    private $ideaImage;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Pca", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var Pca[]|Collections\ArrayCollection
     */
    private $pca;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Report\Item", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var Item[]|Collections\ArrayCollection
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
     * @var Document[]|Collections\ArrayCollection
     */
    private $contractDocument;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Contract\VersionDocument", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var VersionDocument[]|Collections\ArrayCollection
     */
    private $contractVersionDocument;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\Challenge\Image", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var Challenge\Image[]|Collections\ArrayCollection
     */
    private $challengeImage;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\Challenge\Icon", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var Icon[]|Collections\ArrayCollection
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
     * @var Loi[]|Collections\ArrayCollection
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
     * @var Floorplan[]|Collections\ArrayCollection
     */
    private $exhibitionFloorplan;
    /**
     * @ORM\OneToMany(targetEntity="Invoice\Entity\Reminder", cascade={"persist"}, mappedBy="contentType")
     * @Annotation\Exclude()
     * @var Reminder[]|Collections\ArrayCollection()
     */
    private $reminder;

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
        $this->pca = new Collections\ArrayCollection();
        $this->ideaDocument = new Collections\ArrayCollection();
        $this->ideaImage = new Collections\ArrayCollection();
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

    public function __toString(): string
    {
        return (string)$this->contentType;
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function __isset($property)
    {
        return isset($this->$property);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): ContentType
    {
        $this->id = $id;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): ContentType
    {
        $this->description = $description;
        return $this;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function setContentType(?string $contentType): ContentType
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): ContentType
    {
        $this->extension = $extension;
        return $this;
    }

    public function getProjectLogo()
    {
        return $this->projectLogo;
    }

    public function setProjectLogo($projectLogo): ContentType
    {
        $this->projectLogo = $projectLogo;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): ContentType
    {
        $this->image = $image;
        return $this;
    }

    public function getContentImage()
    {
        return $this->contentImage;
    }

    public function setContentImage($contentImage): ContentType
    {
        $this->contentImage = $contentImage;
        return $this;
    }

    public function getPressArticle()
    {
        return $this->pressArticle;
    }

    public function setPressArticle($pressArticle): ContentType
    {
        $this->pressArticle = $pressArticle;
        return $this;
    }

    public function getProgramNna()
    {
        return $this->programNna;
    }

    public function setProgramNna($programNna): ContentType
    {
        $this->programNna = $programNna;
        return $this;
    }

    public function getProgramDoa()
    {
        return $this->programDoa;
    }

    public function setProgramDoa($programDoa): ContentType
    {
        $this->programDoa = $programDoa;
        return $this;
    }

    public function getParentDoa()
    {
        return $this->parentDoa;
    }

    public function setParentDoa($parentDoa): ContentType
    {
        $this->parentDoa = $parentDoa;
        return $this;
    }

    public function getAffiliationDoa()
    {
        return $this->affiliationDoa;
    }

    public function setAffiliationDoa($affiliationDoa): ContentType
    {
        $this->affiliationDoa = $affiliationDoa;
        return $this;
    }

    public function getContactDnd()
    {
        return $this->contactDnd;
    }

    public function setContactDnd($contactDnd): ContentType
    {
        $this->contactDnd = $contactDnd;
        return $this;
    }

    public function getContactPhoto()
    {
        return $this->contactPhoto;
    }

    public function setContactPhoto($contactPhoto): ContentType
    {
        $this->contactPhoto = $contactPhoto;
        return $this;
    }

    public function getOrganisationLogo()
    {
        return $this->organisationLogo;
    }

    public function setOrganisationLogo($organisationLogo): ContentType
    {
        $this->organisationLogo = $organisationLogo;
        return $this;
    }

    public function getPublication()
    {
        return $this->publication;
    }

    public function setPublication($publication): ContentType
    {
        $this->publication = $publication;
        return $this;
    }

    public function getBadgeAttachment()
    {
        return $this->badgeAttachment;
    }

    public function setBadgeAttachment($badgeAttachment): ContentType
    {
        $this->badgeAttachment = $badgeAttachment;
        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result): ContentType
    {
        $this->result = $result;
        return $this;
    }

    public function getWorkpackageDocument()
    {
        return $this->workpackageDocument;
    }

    public function setWorkpackageDocument($workpackageDocument): ContentType
    {
        $this->workpackageDocument = $workpackageDocument;
        return $this;
    }

    public function getPoster()
    {
        return $this->poster;
    }

    public function setPoster($poster): ContentType
    {
        $this->poster = $poster;
        return $this;
    }

    public function getIdeaDocument()
    {
        return $this->ideaDocument;
    }

    public function setIdeaDocument($ideaDocument): ContentType
    {
        $this->ideaDocument = $ideaDocument;
        return $this;
    }

    public function getIdeaImage()
    {
        return $this->ideaImage;
    }

    public function setIdeaImage($ideaImage): ContentType
    {
        $this->ideaImage = $ideaImage;
        return $this;
    }

    public function getPca()
    {
        return $this->pca;
    }

    public function setPca($pca): ContentType
    {
        $this->pca = $pca;
        return $this;
    }

    public function getProjectReportItem()
    {
        return $this->projectReportItem;
    }

    public function setProjectReportItem($projectReportItem): ContentType
    {
        $this->projectReportItem = $projectReportItem;
        return $this;
    }

    public function getProjectDocument()
    {
        return $this->projectDocument;
    }

    public function setProjectDocument($projectDocument): ContentType
    {
        $this->projectDocument = $projectDocument;
        return $this;
    }

    public function getVersionDocument()
    {
        return $this->versionDocument;
    }

    public function setVersionDocument($versionDocument): ContentType
    {
        $this->versionDocument = $versionDocument;
        return $this;
    }

    public function getContractDocument()
    {
        return $this->contractDocument;
    }

    public function setContractDocument($contractDocument): ContentType
    {
        $this->contractDocument = $contractDocument;
        return $this;
    }

    public function getContractVersionDocument()
    {
        return $this->contractVersionDocument;
    }

    public function setContractVersionDocument($contractVersionDocument): ContentType
    {
        $this->contractVersionDocument = $contractVersionDocument;
        return $this;
    }

    public function getChallengeImage()
    {
        return $this->challengeImage;
    }

    public function setChallengeImage($challengeImage): ContentType
    {
        $this->challengeImage = $challengeImage;
        return $this;
    }

    public function getChallengeIcon()
    {
        return $this->challengeIcon;
    }

    public function setChallengeIcon($challengeIcon): ContentType
    {
        $this->challengeIcon = $challengeIcon;
        return $this;
    }

    public function getCalendarDocument()
    {
        return $this->calendarDocument;
    }

    public function setCalendarDocument($calendarDocument): ContentType
    {
        $this->calendarDocument = $calendarDocument;
        return $this;
    }

    public function getLoi()
    {
        return $this->loi;
    }

    public function setLoi($loi): ContentType
    {
        $this->loi = $loi;
        return $this;
    }

    public function getMeetingFloorplan()
    {
        return $this->meetingFloorplan;
    }

    public function setMeetingFloorplan($meetingFloorplan): ContentType
    {
        $this->meetingFloorplan = $meetingFloorplan;
        return $this;
    }

    public function getExhibitionFloorplan()
    {
        return $this->exhibitionFloorplan;
    }

    public function setExhibitionFloorplan($exhibitionFloorplan): ContentType
    {
        $this->exhibitionFloorplan = $exhibitionFloorplan;
        return $this;
    }

    public function getReminder()
    {
        return $this->reminder;
    }

    public function setReminder($reminder): ContentType
    {
        $this->reminder = $reminder;
        return $this;
    }
}
