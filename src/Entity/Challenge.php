<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Zend\Form\Annotation;
use Zend\Permissions\Acl\Resource\ResourceInterface;

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
class Challenge extends EntityAbstract implements ResourceInterface
{
    /**
     * @ORM\Column(name="challenge_id",type="integer",length=10,nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="challenge",type="string",length=20,unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-challenge","rows":30})
     * @Annotation\Attributes({"rows":30})
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
     * @ORM\Column(name="description",type="string")
     * @Annotation\Type("\Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-description"})
     * @Annotation\Attributes({"rows":30})
     *
     * @var string
     */
    private $description;
    /**
     * @ORM\Column(name="backcolor",type="string",length=20,unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-background-color"})
     *
     * @var string
     */
    private $backgroundColor;
    /**
     * @ORM\Column(name="frontcolor",type="string",length=20,unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-front-color"})
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
     * Class constructor.
     */
    public function __construct()
    {
        $this->projectChallenge = new Collections\ArrayCollection();
        $this->boothChallenge   = new Collections\ArrayCollection();
        $this->ideaChallenge    = new Collections\ArrayCollection();
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
     * toString returns the name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->challenge;
    }

    /**
     * Auto-generate an abstract of a article-item.
     *
     * @return string
     */
    public function parseAbstract()
    {
        $arrWords = explode(' ', strip_tags($this->description));

        return implode(' ', array_slice($arrWords, 0, 40)) . '...';
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
     * @return Challenge
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * @param string $challenge
     *
     * @return Challenge
     */
    public function setChallenge($challenge)
    {
        $this->challenge = $challenge;

        return $this;
    }

    /**
     * @return string
     */
    public function getDocRef()
    {
        return $this->docRef;
    }

    /**
     * @param string $docRef
     *
     * @return Challenge
     */
    public function setDocRef($docRef)
    {
        $this->docRef = $docRef;

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
     * @return Challenge
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     *
     * @return Challenge
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrontColor()
    {
        return $this->frontColor;
    }

    /**
     * @param string $frontColor
     *
     * @return Challenge
     */
    public function setFrontColor($frontColor)
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
    public function setProjectChallenge($projectChallenge)
    {
        $this->projectChallenge = $projectChallenge;

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
    public function setIdeaChallenge($ideaChallenge)
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
    public function setBoothChallenge($boothChallenge)
    {
        $this->boothChallenge = $boothChallenge;

        return $this;
    }
}
