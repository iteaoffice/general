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

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\Form\Annotation;
use Zend\Permissions\Acl\Resource\ResourceInterface;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;

use General\Entity\EntityAbstract;

/**
 * Entity for the Country.
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="General\Repository\Country")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("general_gender")
 *
 * @category    General
 * @package     Entity
 */
class Country extends EntityAbstract implements ResourceInterface
{
    /**
     * @ORM\Column(name="country_id",type="integer",nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="country_cd",type="string",length=2, unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country-cd"})
     * @var string
     */
    private $cd;
    /**
     * @ORM\Column(name="country",type="string",length=80, unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country"})
     * @var string
     */
    private $country;
    /**
     * @ORM\Column(name="iso3",type="string",length=20)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-iso3"})
     * @var string
     */
    private $iso3;
    /**
     * @ORM\Column(name="numcode",type="integer",length=6)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-numcode"})
     * @var int
     */
    private $numcode;
    /**
     * @ORM\Column(name="country_vat",type="string",length=2,nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-vat"})
     * @var int
     */
    private $countryVat;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Eu", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     * @var \General\Entity\Eu
     */
    private $eu;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Eureka", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     * @var \General\Entity\Eureka
     */
    private $eureka;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Itac", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     * @var \General\Entity\Eureka
     */
    private $itac;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Address", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     * @var \Contact\Entity\Address[]
     */
    private $address;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\Organisation", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     * @var \Organisation\Entity\Organisation[]
     */
    private $organisation;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\IctOrganisation", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     * @var \Organisation\Entity\IctOrganisation[]
     */
    private $ictOrganisation;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\Vat", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     * @var \General\Entity\Vat[]
     */
    private $vat;
    /**
     * @ORM\OneToMany(targetEntity="Program\Entity\Funder", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     * @var \Program\Entity\Funder[]
     */
    private $funder;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Flag", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     * @var \General\Entity\Flag
     */
    private $flag;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->address         = new Collections\ArrayCollection();
        $this->organisation    = new Collections\ArrayCollection();
        $this->ictOrganisation = new Collections\ArrayCollection();
        $this->vat             = new Collections\ArrayCollection();
        $this->funder          = new Collections\ArrayCollection();
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
        return (string)$this->country;
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
                        'name'       => 'country',
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
                                    'max'      => 80,
                                ),
                            ),
                        ),
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'       => 'cd',
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
                                    'max'      => 2,
                                ),
                            ),
                        ),
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'       => 'iso3',
                        'required'   => false,
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
                                    'max'      => 3,
                                ),
                            ),
                        ),
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'       => 'numcode',
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
                                    'max'      => 6,
                                ),
                            ),
                        ),
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'       => 'countryVat',
                        'required'   => false,
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
                                    'max'      => 2,
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

    /**
     * Needed for the hydration of form elements
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return array(
            'address'      => $this->address,
            'organisation' => $this->organisation,
            'vat'          => $this->vat
        );
    }

    public function populate()
    {
        return $this->getArrayCopy();
    }

    /**
     * New function needed to make the hydrator happy
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
     * New function needed to make the hydrator happy
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
     * @param \General\Entity\Eu[] $eu
     */
    public function setEu($eu)
    {
        $this->eu = $eu;
    }

    /**
     * @return \General\Entity\Eu[]
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
     * @param \Contact\Entity\Address[] $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return \Contact\Entity\Address[]
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param \Organisation\Entity\IctOrganisation[] $ictOrganisation
     */
    public function setIctOrganisation($ictOrganisation)
    {
        $this->ictOrganisation = $ictOrganisation;
    }

    /**
     * @return \Organisation\Entity\IctOrganisation[]
     */
    public function getIctOrganisation()
    {
        return $this->ictOrganisation;
    }

    /**
     * @param \Organisation\Entity\Organisation[] $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @return \Organisation\Entity\Organisation[]
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
     * @param \Program\Entity\Funder[] $funder
     */
    public function setFunder($funder)
    {
        $this->funder = $funder;
    }

    /**
     * @return \Program\Entity\Funder[]
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
}
