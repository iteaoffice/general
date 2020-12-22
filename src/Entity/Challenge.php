<?php

/**
 * ITEA Office all rights reserved
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use General\Entity\Challenge\Icon;
use General\Entity\Challenge\Image;
use General\Entity\Challenge\Pdf;
use General\Entity\Challenge\Type;
use Laminas\Form\Annotation;
use Program\Entity\Call\Call;
use Project\Entity\Idea\Tool;
use Project\Entity\Result\Result;

/**
 * @ORM\Table(name="challenge")
 * @ORM\Entity(repositoryClass="General\Repository\Challenge")
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectPropertyHydrator")
 * @Annotation\Name("general_challenge")
 */
class Challenge extends AbstractEntity
{
    /**
     * @ORM\Column(name="challenge_id",type="integer",options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Type("\Laminas\Form\Element\Hidden")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="challenge",type="string",unique=true)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-challenge-challenge-label","help-block":"txt-challenge-challenge-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-challenge-placeholder"})
     *
     * @var string
     */
    private $challenge;
    /**
     * @ORM\Column(name="docref", type="string", nullable=false, unique=true)
     * @Gedmo\Slug(fields={"challenge"})
     * @Annotation\Exclude()
     *
     * @var string
     */
    private $docRef;
    /**
     * @ORM\Column(name="sequence", type="integer", options={"unsigned":true})
     * @Annotation\Type("\Laminas\Form\Element\Number")
     * @Annotation\Options({"label":"txt-challenge-sequence-label","help-block":"txt-challenge-sequence-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-sequence-placeholder"})
     * @var int
     */
    private $sequence;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Challenge\Type", inversedBy="challenge", cascade={"persist"})
     * @ORM\JoinColumn(name="type_id", referencedColumnName="type_id", nullable=true)
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
     * @var Type
     */
    private $type;
    /**
     * @ORM\Column(name="html", type="text", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-challenge-html-label","help-block": "txt-challenge-html-explanation"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-html-placeholder"})
     * @Annotation\Attributes({"id":"html_challenge"})
     *
     * @var string
     */
    private $html;
    /**
     * @ORM\Column(name="css", type="text", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-challenge-css-label","help-block": "txt-challenge-css-explanation"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-css-placeholder"})
     * @Annotation\Attributes({"id":"css_challenge"})
     *
     * @var string
     */
    private $css;
    /**
     * @ORM\Column(name="sources",type="text", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-challenge-sources-label","help-block":"txt-challenge-sources-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-sources-placeholder"})
     *
     * @var string
     */
    private $sources;
    /**
     * @ORM\Column(name="abstract", type="text", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-challenge-abstract-label","help-block": "txt-challenge-abstract-explanation"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-abstract-placeholder","rows":6})
     *
     * @var string
     */
    private $abstract;
    /**
     * @ORM\Column(name="background_image", type="smallint", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Number")
     * @Annotation\Options({"label":"txt-challenge-background-image-label","help-block": "txt-challenge-backgrond-image-explanation"})
     *
     * @var int
     */
    private $backgroundImage;
    /**
     * @ORM\Column(name="backcolor",type="string",unique=false)
     * @Annotation\Type("\Laminas\Form\Element\Color")
     * @Annotation\Options({"label":"txt-challenge-background-color-label","help-block":"txt-challenge-background-color-help-block"})
     *
     * @var string
     */
    private $backgroundColor;
    /**
     * @ORM\Column(name="frontcolor",type="string",unique=false)
     * @Annotation\Type("\Laminas\Form\Element\Color")
     * @Annotation\Options({"label":"txt-challenge-front-color-label","help-block":"txt-challenge-front-color-help-block"})
     *
     * @var string
     */
    private $frontColor;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Project\Challenge", cascade={"persist"}, mappedBy="challenge")
     * @Annotation\Exclude()
     *
     * @var \Project\Entity\Project\Challenge[]|Collections\ArrayCollection
     */
    private $projectChallenge;
    /**
     * @ORM\ManyToMany(targetEntity="Project\Entity\Result\Result", cascade={"persist"}, mappedBy="challenge")
     * @Annotation\Exclude()
     *
     * @var Result[]|Collections\ArrayCollection
     */
    private $result;
    /**
     * @ORM\ManyToMany(targetEntity="Project\Entity\Idea\Tool", cascade={"persist"}, mappedBy="challenge")
     * @Annotation\Exclude()
     *
     * @var Tool[]|Collections\ArrayCollection
     */
    private $tool;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Idea\Tool", cascade={"persist"}, mappedBy="pinnedChallenge")
     * @Annotation\Exclude()
     *
     * @var Tool[]|Collections\ArrayCollection
     */
    private $toolPinned;
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
     * @Annotation\Type("\Laminas\Form\Element\File")
     * @Annotation\Options({"label":"txt-challenge-image-label","help-block":"txt-challenge-image-help-block"})
     *
     * @var Image
     */
    private $image;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge\Icon", cascade={"persist","remove"}, mappedBy="challenge")
     * @Annotation\Type("\Laminas\Form\Element\File")
     * @Annotation\Options({"label":"txt-challenge-icon-label","help-block":"txt-challenge-icon-help-block"})
     *
     * @var Icon
     */
    private $icon;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge\Idea\Poster\Image", cascade={"persist","remove"}, mappedBy="challenge")
     * @Annotation\Type("\Laminas\Form\Element\File")
     * @Annotation\Options({"label":"txt-challenge-idea-poster-image-label","help-block":"txt-challenge-image-help-block"})
     *
     * @var Challenge\Idea\Poster\Image
     */
    private $ideaPosterImage;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge\Idea\Poster\Icon", cascade={"persist","remove"}, mappedBy="challenge")
     * @Annotation\Type("\Laminas\Form\Element\File")
     * @Annotation\Options({"label":"txt-challenge-idea-poster-icon-label","help-block":"txt-challenge-icon-help-block"})
     *
     * @var Challenge\Idea\Poster\Icon
     */
    private $ideaPosterIcon;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge\Pdf", cascade={"persist","remove"}, mappedBy="challenge")
     * @Annotation\Type("\Laminas\Form\Element\File")
     * @Annotation\Options({"label":"txt-challenge-pdf-label","help-block":"txt-challenge-pdf-help-block"})
     *
     * @var Pdf
     */
    private $pdf;
    /**
     * @ORM\ManyToMany(targetEntity="Program\Entity\Call\Call", inversedBy="challenge")
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
     *                  "id":"DESC"}
     *              }
     *          }
     *      }
     * )
     * @Annotation\Attributes({"label":"txt-challenge-program-call-label"})
     *
     * @var Call[]|Collections\ArrayCollection
     */
    private $call;

    public function __construct()
    {
        $this->result           = new Collections\ArrayCollection();
        $this->call             = new Collections\ArrayCollection();
        $this->projectChallenge = new Collections\ArrayCollection();
        $this->boothChallenge   = new Collections\ArrayCollection();
        $this->ideaChallenge    = new Collections\ArrayCollection();
        $this->tool             = new Collections\ArrayCollection();
        $this->toolPinned       = new Collections\ArrayCollection();
        $this->sequence         = 1;
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

    public function getChallenge(): ?string
    {
        return $this->challenge;
    }

    public function setChallenge(string $challenge): Challenge
    {
        $this->challenge = $challenge;

        return $this;
    }

    public function getDocRef(): ?string
    {
        return $this->docRef;
    }

    public function setDocRef(string $docRef): Challenge
    {
        $this->docRef = $docRef;

        return $this;
    }

    public function getSources(): ?string
    {
        return $this->sources;
    }

    public function setSources(string $sources): Challenge
    {
        $this->sources = $sources;

        return $this;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence): Challenge
    {
        $this->sequence = $sequence;

        return $this;
    }

    public function getType(): ?Challenge\Type
    {
        return $this->type;
    }

    public function setType(Challenge\Type $type): Challenge
    {
        $this->type = $type;

        return $this;
    }

    public function getCall()
    {
        return $this->call;
    }

    public function setCall($call): Challenge
    {
        $this->call = $call;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): Challenge
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getFrontColor(): ?string
    {
        return $this->frontColor;
    }

    public function setFrontColor(string $frontColor): Challenge
    {
        $this->frontColor = $frontColor;

        return $this;
    }

    public function getProjectChallenge()
    {
        return $this->projectChallenge;
    }

    public function setProjectChallenge($projectChallenge): Challenge
    {
        $this->projectChallenge = $projectChallenge;

        return $this;
    }

    public function getResult(): iterable
    {
        return $this->result;
    }

    public function setResult($result): Challenge
    {
        $this->result = $result;

        return $this;
    }

    public function getIdeaChallenge()
    {
        return $this->ideaChallenge;
    }

    public function setIdeaChallenge($ideaChallenge): Challenge
    {
        $this->ideaChallenge = $ideaChallenge;

        return $this;
    }

    public function getBoothChallenge()
    {
        return $this->boothChallenge;
    }

    public function setBoothChallenge($boothChallenge): Challenge
    {
        $this->boothChallenge = $boothChallenge;

        return $this;
    }

    public function getImage(): ?Challenge\Image
    {
        return $this->image;
    }

    public function setImage($image): Challenge
    {
        $this->image = $image;

        return $this;
    }

    public function getIcon(): ?Challenge\Icon
    {
        return $this->icon;
    }

    public function setIcon($icon): Challenge
    {
        $this->icon = $icon;

        return $this;
    }

    public function getPdf(): ?Challenge\Pdf
    {
        return $this->pdf;
    }

    public function setPdf($pdf): Challenge
    {
        $this->pdf = $pdf;

        return $this;
    }

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

    public function getBackgroundImage(): ?int
    {
        return $this->backgroundImage;
    }

    public function setBackgroundImage(int $backgroundImage): Challenge
    {
        $this->backgroundImage = $backgroundImage;
        return $this;
    }

    public function getTool()
    {
        return $this->tool;
    }

    public function setTool($tool): Challenge
    {
        $this->tool = $tool;
        return $this;
    }

    public function getToolPinned()
    {
        return $this->toolPinned;
    }

    public function setToolPinned($toolPinned): Challenge
    {
        $this->toolPinned = $toolPinned;
        return $this;
    }

    public function getIdeaPosterImage(): ?Challenge\Idea\Poster\Image
    {
        return $this->ideaPosterImage;
    }

    public function setIdeaPosterImage(?Challenge\Idea\Poster\Image $ideaPosterImage): Challenge
    {
        $this->ideaPosterImage = $ideaPosterImage;
        return $this;
    }

    public function getIdeaPosterIcon(): ?Challenge\Idea\Poster\Icon
    {
        return $this->ideaPosterIcon;
    }

    public function setIdeaPosterIcon(?Challenge\Idea\Poster\Icon $ideaPosterIcon): Challenge
    {
        $this->ideaPosterIcon = $ideaPosterIcon;
        return $this;
    }
}
