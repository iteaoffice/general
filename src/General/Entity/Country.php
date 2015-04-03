<?php
/**
 * ITEA copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
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
 * Entity for the Country.
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="General\Repository\Country")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("general_gender")
 *
 * @category General
 */
class Country extends EntityAbstract implements ResourceInterface
{
    /**
     * @ORM\Column(name="country_id",type="integer",nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="country_cd",type="string",length=2, unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country-cd"})
     *
     * @var string
     */
    private $cd;
    /**
     * @ORM\Column(name="country",type="string",length=80, unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country"})
     *
     * @var string
     */
    private $country;
    /**
     * @ORM\Column(name="docRef",type="string",length=80, unique=true)
     * @Gedmo\Slug(fields={"country"})
     * @Annotation\Exclude()
     *
     * @var string
     */
    private $docRef;
    /**
     * @ORM\Column(name="iso3",type="string",length=20)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-iso3"})
     *
     * @var string
     */
    private $iso3;
    /**
     * @ORM\Column(name="numcode",type="integer",length=6)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-numcode"})
     *
     * @var int
     */
    private $numcode;
    /**
     * @ORM\Column(name="country_vat",type="string",length=2,nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-vat"})
     *
     * @var int
     */
    private $countryVat;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Eu", cascade={"persist"}, mappedBy="country", fetch="EXTRA_LAZY")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Eu
     */
    private $eu;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Eureka", cascade={"persist"}, mappedBy="country", fetch="EXTRA_LAZY")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Eureka
     */
    private $eureka;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Itac", cascade={"persist"}, mappedBy="country", fetch="EXTRA_LAZY")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Eureka
     */
    private $itac;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Address", cascade={"persist"}, mappedBy="country", fetch="EXTRA_LAZY")
     * @Annotation\Exclude()
     *
     * @var \Contact\Entity\Address[]|Collections\ArrayCollection
     */
    private $address;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\Organisation", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Organisation\Entity\Organisation[]|Collections\ArrayCollection
     */
    private $organisation;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\IctOrganisation", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Organisation\Entity\IctOrganisation[]|Collections\ArrayCollection
     */
    private $ictOrganisation;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\Vat", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Vat[]|Collections\ArrayCollection
     */
    private $vat;
    /**
     * @ORM\OneToMany(targetEntity="Program\Entity\Funder", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Program\Entity\Funder[]|Collections\ArrayCollection
     */
    private $funder;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Flag", cascade={"persist"}, mappedBy="country", fetch="EXTRA_LAZY")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Flag
     */
    private $flag;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Evaluation\Evaluation", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Project\Entity\Evaluation\Evaluation[]|Collections\ArrayCollection
     */
    private $evaluation;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Rationale", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Project\Entity\Rationale[]|Collections\ArrayCollection
     */
    private $rationale;
    /**
     * @ORM\OneToMany(targetEntity="Ambassador\Entity\Ambassador", cascade={"persist","remove"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Ambassador\Entity\Ambassador[]|Collections\ArrayCollection
     */
    private $ambassador;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->address = new Collections\ArrayCollection();
        $this->organisation = new Collections\ArrayCollection();
        $this->ictOrganisation = new Collections\ArrayCollection();
        $this->rationale = new Collections\ArrayCollection();
        $this->vat = new Collections\ArrayCollection();
        $this->funder = new Collections\ArrayCollection();
        $this->evaluation = new Collections\ArrayCollection();
        $this->ambassador = new Collections\ArrayCollection();
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
        return (string) $this->country;
    }

    /**
     * Returns the string identifier of the Resource.
     *
     * @return string
     */
    public function getResourceId()
    {
        return __NAMESPACE__.':'.__CLASS__.':'.$this->id;
    }

    /**
     * Set input filter.
     *
     * @param InputFilterInterface $inputFilter
     *
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
            $factory = new InputFactory();
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name'       => 'country',
                        'required'   => true,
                        'filters'    => [
                            ['name' => 'StripTags'],
                            ['name' => 'StringTrim'],
                        ],
                        'validators' => [
                            [
                                'name'    => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min'      => 1,
                                    'max'      => 80,
                                ],
                            ],
                        ],
                    ]
                )
            );
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name'       => 'cd',
                        'required'   => true,
                        'filters'    => [
                            ['name' => 'StripTags'],
                            ['name' => 'StringTrim'],
                        ],
                        'validators' => [
                            [
                                'name'    => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min'      => 1,
                                    'max'      => 2,
                                ],
                            ],
                        ],
                    ]
                )
            );
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name'       => 'iso3',
                        'required'   => false,
                        'filters'    => [
                            ['name' => 'StripTags'],
                            ['name' => 'StringTrim'],
                        ],
                        'validators' => [
                            [
                                'name'    => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min'      => 1,
                                    'max'      => 3,
                                ],
                            ],
                        ],
                    ]
                )
            );
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name'       => 'numcode',
                        'required'   => true,
                        'filters'    => [
                            ['name' => 'StripTags'],
                            ['name' => 'StringTrim'],
                        ],
                        'validators' => [
                            [
                                'name'    => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min'      => 1,
                                    'max'      => 6,
                                ],
                            ],
                        ],
                    ]
                )
            );
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name'       => 'countryVat',
                        'required'   => false,
                        'filters'    => [
                            ['name' => 'StripTags'],
                            ['name' => 'StringTrim'],
                        ],
                        'validators' => [
                            [
                                'name'    => 'StringLength',
                                'options' => [
                                    'encoding' => 'UTF-8',
                                    'min'      => 1,
                                    'max'      => 2,
                                ],
                            ],
                        ],
                    ]
                )
            );
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    /**
     * Needed for the hydration of form elements.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'address'      => $this->address,
            'organisation' => $this->organisation,
            'vat'          => $this->vat,
        ];
    }

    public function populate()
    {
        return $this->getArrayCopy();
    }

    /**
     * New function needed to make the hydrator happy.
     *
     * @param Collections\Collection $vatCollection
     */
    public function addVat(Collections\Collection $vatCollection)
    {
        foreach ($vatCollection as $vat) {
            $this->vat->add($vat);
        }
    }

    /**
     * New function needed to make the hydrator happy.
     *
     * @param Collections\Collection $vatCollection
     */
    public function removeVat(Collections\Collection $vatCollection)
    {
        foreach ($vatCollection as $vat) {
            $this->vat->removeElement($vat);
        }
    }

    /**
     * @param string $cd
     */
    public function setCd($cd)
    {
        $this->cd = $cd;
    }

    /**
     * @return string
     */
    public function getCd()
    {
        return $this->cd;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param \General\Entity\Eu[]|Collections\ArrayCollection $eu
     */
    public function setEu($eu)
    {
        $this->eu = $eu;
    }

    /**
     * @return \General\Entity\Eu[]|Collections\ArrayCollection
     */
    public function getEu()
    {
        return $this->eu;
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
     * @param string $iso3
     */
    public function setIso3($iso3)
    {
        $this->iso3 = $iso3;
    }

    /**
     * @return string
     */
    public function getIso3()
    {
        return $this->iso3;
    }

    /**
     * @param int $numcode
     */
    public function setNumcode($numcode)
    {
        $this->numcode = $numcode;
    }

    /**
     * @return int
     */
    public function getNumcode()
    {
        return $this->numcode;
    }

    /**
     * @param int $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @return int
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param \Contact\Entity\Address[]|Collections\ArrayCollection $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return \Contact\Entity\Address[]|Collections\ArrayCollection
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param \Organisation\Entity\IctOrganisation[]|Collections\ArrayCollection $ictOrganisation
     */
    public function setIctOrganisation($ictOrganisation)
    {
        $this->ictOrganisation = $ictOrganisation;
    }

    /**
     * @return \Organisation\Entity\IctOrganisation[]|Collections\ArrayCollection
     */
    public function getIctOrganisation()
    {
        return $this->ictOrganisation;
    }

    /**
     * @param \Organisation\Entity\Organisation[]|Collections\ArrayCollection $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @return \Organisation\Entity\Organisation[]|Collections\ArrayCollection
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param int $countryVat
     */
    public function setCountryVat($countryVat)
    {
        $this->countryVat = $countryVat;
    }

    /**
     * @return int
     */
    public function getCountryVat()
    {
        return $this->countryVat;
    }

    /**
     * @param \Program\Entity\Funder[]|Collections\ArrayCollection $funder
     */
    public function setFunder($funder)
    {
        $this->funder = $funder;
    }

    /**
     * @return \Program\Entity\Funder[]|Collections\ArrayCollection
     */
    public function getFunder()
    {
        return $this->funder;
    }

    /**
     * @param \General\Entity\Eureka $eureka
     */
    public function setEureka($eureka)
    {
        $this->eureka = $eureka;
    }

    /**
     * @return \General\Entity\Eureka
     */
    public function getEureka()
    {
        return $this->eureka;
    }

    /**
     * @param \General\Entity\Flag $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * @return \General\Entity\Flag
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param \General\Entity\Eureka $itac
     */
    public function setItac($itac)
    {
        $this->itac = $itac;
    }

    /**
     * @return \General\Entity\Eureka
     */
    public function getItac()
    {
        return $this->itac;
    }

    /**
     * @param string $docRef
     */
    public function setDocRef($docRef)
    {
        $this->docRef = $docRef;
    }

    /**
     * @return string
     */
    public function getDocRef()
    {
        return $this->docRef;
    }

    /**
     * @param \Project\Entity\Evaluation\Evaluation[]|Collections\ArrayCollection $evaluation
     */
    public function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;
    }

    /**
     * @return \Project\Entity\Evaluation\Evaluation[]|Collections\ArrayCollection
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Rationale[]
     */
    public function getRationale()
    {
        return $this->rationale;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Rationale[] $rationale
     */
    public function setRationale($rationale)
    {
        $this->rationale = $rationale;
    }

    /**
     * @param Collections\ArrayCollection|\Ambassador\Entity\Ambassador[] $ambassador
     */
    public function getAmbassasor(){
        return $this->ambassador;
    }

    /**
     * @param Collections\ArrayCollection|\Ambassador\Entity\Ambassador[] $ambassador
     */
    public function setAmbassasor(Collections\ArrayCollection $ambassador){
        $this->ambassador = $ambassador;
        return $this;
    }
}
