<?php
/**
 * ITEA Office all rights reserved
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
use Gedmo\Mapping\Annotation as Gedmo;
use Zend\Form\Annotation;

/**
 * Entity for the General.
 *
 * @ORM\Table(name="challenge")
 * @ORM\Entity(repositoryClass="General\Repository\Challenge")
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("general_challenge")
 *
 * @category General
 */
class Challenge extends AbstractEntity
{
    /**
     * @ORM\Column(name="challenge_id",type="integer",nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Type("\Zend\Form\Element\Hidden")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="challenge",type="string",unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-challenge-challenge-label","help-block":"txt-challenge-challenge-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-challenge-placeholder"})
     *
     * @var string
     */
    private $challenge;
    /**
     * @ORM\Column(name="docref", type="string", length=255, nullable=false, unique=true)
     * @Gedmo\Slug(fields={"challenge"})
     * @Annotation\Exclude()
     *
     * @var string
     */
    private $docRef;
    /**
     * @ORM\Column(name="sequence", type="integer", nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Number")
     * @Annotation\Options({"label":"txt-challenge-sequence-label","help-block":"txt-challenge-sequence-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-sequence-placeholder"})
     * @var int
     */
    private $sequence;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Challenge\Type", inversedBy="challenge", cascade={"persist"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="type_id", referencedColumnName="type_id", nullable=true)
     * })
     * @Annotation\Type("DoctrineORMModule\Form\Element\EntitySelect")
     * @Annotation\Options({
     *      "target_class":"General\Entity\Challenge\Type",
     *      "allow_empty":"true",
     *      "empty_option":"Select a type",
     *      "find_method":{
     *          "name":"findBy",
     *          "params": {
     *              "criteria":{},
     *              "orderBy":{
     *                  "type":"ASC"}
     *              }
     *          }
     *      }
     * )
     * @Annotation\Attributes({"label":"txt-type"})
     * @Annotation\Options({"help-block":"txt-challenge-type-help-block"})
     *
     * @var \General\Entity\Challenge\Type
     */
    private $type;
    /**
     * @ORM\Column(name="description",type="string")
     * @Annotation\Type("\Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-challenge-description-label","help-block":"txt-challenge-description-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-description-placeholder"})
     * @Annotation\Attributes({"rows":30})
     *
     * @var string
     */
    private $description;
    /**
     * @ORM\Column(name="html", type="text", nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-challenge-html-label","help-block": "txt-challenge-html-explanation"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-html-placeholder"})
     * @Annotation\Attributes({"rows":"25"})
     *
     * @var string
     */
    private $html;
    /**
     * @ORM\Column(name="css", type="text", nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-challenge-css-label","help-block": "txt-challenge-css-explanation"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-css-placeholder"})
     * @Annotation\Attributes({"rows":"25"})
     *
     * @var string
     */
    private $css;
    /**
     * @ORM\Column(name="sources",type="text", nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-challenge-sources-label","help-block":"txt-challenge-sources-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-sources-placeholder"})
     *
     * @var string
     */
    private $sources;
    /**
     * @ORM\Column(name="abstract", type="text", nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-challenge-abstract-label","help-block": "txt-challenge-abstract-explanation"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-abstract-placeholder"})
     *
     * @var string
     */
    private $abstract;
    /**
     * @ORM\Column(name="background_image", type="smallint", nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Number")
     * @Annotation\Options({"label":"txt-challenge-background-image-label","help-block": "txt-challenge-backgrond-image-explanation"})
     *
     * @var int
     */
    private $backgroundImage;
    /**
     * @ORM\Column(name="backcolor",type="string",length=20,unique=false)
     * @Annotation\Type("\Zend\Form\Element\Color")
     * @Annotation\Options({"label":"txt-challenge-background-color-label","help-block":"txt-challenge-background-color-help-block"})
     *
     * @var string
     */
    private $backgroundColor;
    /**
     * @ORM\Column(name="frontcolor",type="string",length=20,unique=false)
     * @Annotation\Type("\Zend\Form\Element\Color")
     * @Annotation\Options({"label":"txt-challenge-front-color-label","help-block":"txt-challenge-front-color-help-block"})
     *
     * @var string
     */
    private $frontColor;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Challenge", cascade={"persist"}, mappedBy="challenge")
     * @Annotation\Exclude()
     *
     * @var \Project\Entity\Challenge[]|Collections\ArrayCollection
     */
    private $projectChallenge;
    /**
     * @ORM\ManyToMany(targetEntity="Project\Entity\Result\Result", cascade={"persist"}, mappedBy="challenge")
     * @Annotation\Exclude()
     *
     * @var \Project\Entity\Result\Result[]|Collections\ArrayCollection
     */
    private $result;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Idea\Challenge", cascade={"persist"}, mappedBy="challenge")
     * @Annotation\Exclude()
     *
     * @var \Project\Entity\Idea\Challenge[]|Collections\ArrayCollection
     */
    private $ideaChallenge;
    /**
     * @ORM\OneToMany(targetEntity="Event\Entity\Booth\Challenge", cascade={"persist"}, mappedBy="challenge")
     * @Annotation\Exclude()
     *
     * @var \Event\Entity\Booth\Challenge[]|Collections\ArrayCollection
     */
    private $boothChallenge;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge\Image", cascade={"persist","remove"}, mappedBy="challenge")
     * @Annotation\Type("\Zend\Form\Element\File")
     * @Annotation\Options({"label":"txt-challenge-image-label","help-block":"txt-challenge-image-help-block"})
     *
     * @var \General\Entity\Challenge\Image
     */
    private $image;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge\Icon", cascade={"persist","remove"}, mappedBy="challenge")
     * @Annotation\Type("\Zend\Form\Element\File")
     * @Annotation\Options({"label":"txt-challenge-icon-label","help-block":"txt-challenge-icon-help-block"})
     *
     * @var \General\Entity\Challenge\Icon
     */
    private $icon;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge\Pdf", cascade={"persist","remove"}, mappedBy="challenge")
     * @Annotation\Type("\Zend\Form\Element\File")
     * @Annotation\Options({"label":"txt-challenge-pdf-label","help-block":"txt-challenge-pdf-help-block"})
     *
     * @var \General\Entity\Challenge\Pdf
     */
    private $pdf;
    /**
     * @ORM\ManyToMany(targetEntity="Program\Entity\Call\Call", inversedBy="challenge", orphanRemoval=true)
     * @ORM\OrderBy({"call"="ASC"})
     * @ORM\JoinTable(name="challenge_call",
     *            joinColumns={@ORM\JoinColumn(name="challenge_id", referencedColumnName="challenge_id")},
     *            inverseJoinColumns={@ORM\JoinColumn(name="programcall_id", referencedColumnName="programcall_id")}
     * )
     * @Annotation\Type("DoctrineORMModule\Form\Element\EntityMultiCheckbox")
     * @Annotation\Options({
     *      "help-block":"txt-challenge-program-call-help-block",
     *      "target_class":"Program\Entity\Call\Call",
     *      "find_method":{
     *          "name":"findBy",
     *          "params": {
     *              "criteria":{},
     *              "orderBy":{
     *                  "call":"DESC"}
     *              }
     *          }
     *      }
     * )
     * @Annotation\Attributes({"label":"txt-challenge-program-call-label"})
     *
     * @var \Program\Entity\Call\Call[]|Collections\ArrayCollection
     */
    private $call;

    public function __construct()
    {
        $this->result = new Collections\ArrayCollection();
        $this->call = new Collections\ArrayCollection();
        $this->projectChallenge = new Collections\ArrayCollection();
        $this->boothChallenge = new Collections\ArrayCollection();
        $this->ideaChallenge = new Collections\ArrayCollection();
        $this->sequence = 1;
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

    public function __toString(): string
    {
        return (string)$this->challenge;
    }

    public function addCall(Collections\Collection $callCollection): void
    {
        foreach ($callCollection as $call) {
            $this->call->add($call);
        }
    }

    public function removeCall(Collections\Collection $callCollection): void
    {
        foreach ($callCollection as $call) {
            $this->call->removeElement($call);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): Challenge
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getChallenge(): ?string
    {
        return $this->challenge;
    }

    /**
     * @param string $challenge
     *
     * @return Challenge
     */
    public function setChallenge(string $challenge): Challenge
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * @return string
     */
    public function getDocRef(): ?string
    {
        return $this->docRef;
    }

    /**
     * @param string $docRef
     *
     * @return Challenge
     */
    public function setDocRef(string $docRef): Challenge
    {
        $this->docRef = $docRef;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Challenge
     */
    public function setDescription(string $description): Challenge
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getSources(): ?string
    {
        return $this->sources;
    }

    /**
     * @param string $sources
     *
     * @return Challenge
     */
    public function setSources(string $sources): Challenge
    {
        $this->sources = $sources;

        return $this;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param int $sequence
     *
     * @return Challenge
     */
    public function setSequence($sequence): Challenge
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * @return Challenge\Type|null
     */
    public function getType(): ?Challenge\Type
    {
        return $this->type;
    }

    /**
     * @param Challenge\Type $type
     *
     * @return Challenge
     */
    public function setType(Challenge\Type $type): Challenge
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Program\Entity\Call\Call[]
     */
    public function getCall()
    {
        return $this->call;
    }

    /**
     * @param Collections\ArrayCollection|\Program\Entity\Call\Call[] $call
     *
     * @return Challenge
     */
    public function setCall($call): Challenge
    {
        $this->call = $call;

        return $this;
    }

    /**
     * @return string
     */
    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     *
     * @return Challenge
     */
    public function setBackgroundColor(string $backgroundColor): Challenge
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrontColor(): ?string
    {
        return $this->frontColor;
    }

    /**
     * @param string $frontColor
     *
     * @return Challenge
     */
    public function setFrontColor(string $frontColor): Challenge
    {
        $this->frontColor = $frontColor;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Challenge[]
     */
    public function getProjectChallenge()
    {
        return $this->projectChallenge;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Challenge[] $projectChallenge
     *
     * @return Challenge
     */
    public function setProjectChallenge($projectChallenge): Challenge
    {
        $this->projectChallenge = $projectChallenge;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Result\Result[]|iterable
     */
    public function getResult(): iterable
    {
        return $this->result;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Result\Result[] $result
     *
     * @return Challenge
     */
    public function setResult($result): Challenge
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Idea\Challenge[]
     */
    public function getIdeaChallenge()
    {
        return $this->ideaChallenge;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Idea\Challenge[] $ideaChallenge
     *
     * @return Challenge
     */
    public function setIdeaChallenge($ideaChallenge): Challenge
    {
        $this->ideaChallenge = $ideaChallenge;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Event\Entity\Booth\Challenge[]
     */
    public function getBoothChallenge()
    {
        return $this->boothChallenge;
    }

    /**
     * @param Collections\ArrayCollection|\Event\Entity\Booth\Challenge[] $boothChallenge
     *
     * @return Challenge
     */
    public function setBoothChallenge($boothChallenge): Challenge
    {
        $this->boothChallenge = $boothChallenge;

        return $this;
    }

    /**
     * @return Challenge\Image
     */
    public function getImage(): ?Challenge\Image
    {
        return $this->image;
    }

    /**
     * @param Collections\ArrayCollection|Challenge\Image $image
     *
     * @return Challenge
     */
    public function setImage($image): Challenge
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Challenge\Icon
     */
    public function getIcon(): ?Challenge\Icon
    {
        return $this->icon;
    }

    /**
     * @param Collections\ArrayCollection|Challenge\Icon $icon
     *
     * @return Challenge
     */
    public function setIcon($icon): Challenge
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Challenge\Pdf
     */
    public function getPdf(): ?Challenge\Pdf
    {
        return $this->pdf;
    }

    /**
     * @param Collections\ArrayCollection|Challenge\Pdf $pdf
     *
     * @return Challenge
     */
    public function setPdf($pdf): Challenge
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * @return string
     */
    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    public function setAbstract(string $abstract): Challenge
    {
        $this->abstract = $abstract;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(string $html): Challenge
    {
        $this->html = $html;

        return $this;
    }

    public function getCss(): ?string
    {
        return $this->css;
    }

    public function setCss(string $css): Challenge
    {
        $this->css = $css;

        return $this;
    }

    public function getBackgroundImage()
    {
        return $this->backgroundImage;
    }

    public function setBackgroundImage(int $backgroundImage): Challenge
    {
        $this->backgroundImage = $backgroundImage;
        return $this;
    }
}
