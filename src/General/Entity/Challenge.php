<?php
/**
 * Debranova copyright message placeholder
 *
 * @category    General
 * @package     Entity
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Zend\Form\Annotation;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Entity for the General
 *
 * @ORM\Table(name="challenge")
 * @ORM\Entity
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("general_challenge")
 *
 * @category    General
 * @package     Entity
 */
class Challenge extends EntityAbstract implements ResourceInterface
{
    /**
     * @ORM\Column(name="challenge_id",type="integer",length=10,nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="challenge",type="string",length=20,unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-challenge"})
     * @var string
     */
    private $challenge;
    /**
     * @ORM\Column(name="docref", type="string", length=255, nullable=false, unique=true)
     * @Gedmo\Slug(fields={"challenge"})
     * @Annotation\Exclude()
     * @var string
     */
    private $docRef;
    /**
     * @ORM\Column(name="description",type="string")
     * @Annotation\Type("\Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-description"})
     * @var string
     */
    private $description;
    /**
     * @ORM\Column(name="backcolor",type="string",length=20,unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-background-color"})
     * @var string
     */
    private $backgroundColor;
    /**
     * @ORM\Column(name="frontcolor",type="string",length=20,unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-front-color"})
     * @var string
     */
    private $frontColor;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Challenge", cascade={"persist"}, mappedBy="challenge")
     * @Annotation\Exclude()
     * @var \Project\Entity\Challenge[]
     */
    private $projectChallenge;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Contact", cascade={"all"}, mappedBy="challenge")
     * @Annotation\Exclude()
     * @var \Contact\Entity\Contact[]
     * private $contact;
     */

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->projectChallenge = new Collections\ArrayCollection();
    }

    /**
     * Magic Getter
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
     * Magic Setter
     *
     * @param $property
     * @param $value
     *
     * @return void
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * toString returns the name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->challenge;
    }

    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return __NAMESPACE__ . ':' . __CLASS__ . ':' . $this->id;
    }

    /**
     * Set input filter
     *
     * @param InputFilterInterface $inputFilter
     *
     * @return void
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Setting an inputFilter is currently not supported");
    }

    /**
     * @return \Zend\InputFilter\InputFilter|\Zend\InputFilter\InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'       => 'challenge',
                        'required'   => true,
                        'filters'    => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name'    => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min'      => 1,
                                    'max'      => 100,
                                ),
                            ),
                        ),
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'     => 'description',
                        'required' => true,
                        'filters'  => array(
                            array('name' => 'StringTrim'),
                        ),

                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'       => 'backgroundColor',
                        'required'   => true,
                        'filters'    => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name'    => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min'      => 1,
                                    'max'      => 100,
                                ),
                            ),
                        ),
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'       => 'frontColor',
                        'required'   => true,
                        'filters'    => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name'    => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min'      => 1,
                                    'max'      => 100,
                                ),
                            ),
                        ),
                    )
                )
            );

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function populate()
    {
        return $this->getArrayCopy();
    }

    /**
     * Needed for the hydration of form elements
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return array(
            'challenge'        => $this->challenge,
            'description'      => $this->description,
            'backgroundColor'  => $this->backgroundColor,
            'frontColor'       => $this->frontColor,
            'projectChallenge' => $this->projectChallenge,
        );
    }

    /**
     * Auto-generate an abstract of a article-item
     *
     * @return string
     */
    public function parseAbstract()
    {
        $arrWords = explode(' ', strip_tags($this->description));

        return implode(' ', array_slice($arrWords, 0, 40)) . '...';
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
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
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
     */
    public function setChallenge($challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * @return string
     */
    public function getDocRef()
    {
        return $this->docRef;
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
    public function getFrontColor()
    {
        return $this->frontColor;
    }

    /**
     * @param string $frontColor
     */
    public function setFrontColor($frontColor)
    {
        $this->frontColor = $frontColor;
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
     * @return \Project\Entity\Challenge[]
     */
    public function getProjectChallenge()
    {
        return $this->projectChallenge;
    }

    /**
     * @param \Project\Entity\Challenge[] $projectChallenge
     */
    public function setProjectChallenge($projectChallenge)
    {
        $this->projectChallenge = $projectChallenge;
    }
}
