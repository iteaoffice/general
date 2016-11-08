<?php
/**
 * ITEA copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Entity for the General.
 *
 * @ORM\Table(name="vat_type")
 * @ORM\Entity(repositoryClass="General\Repository\VatType")
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("vat_type")
 *
 * @category General
 */
class VatType extends EntityAbstract implements ResourceInterface
{
    const VAT_TYPE_LOCAL = 1;
    const VAT_TYPE_IN_EU_SHIFT = 2;
    const VAT_TYPE_IN_EU_NO_SHIFT = 3;
    const VAT_TYPE_NON_EU = 4;

    /**
     * @ORM\Column(name="type_id",type="integer",nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="type",type="string",length=30, unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-vat-type"})
     *
     * @var string
     */
    private $type;
    /**
     * @ORM\Column(name="description",type="string",length=64)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-description"})
     *
     * @var string
     */
    private $description;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Vat", inversedBy="type", cascade={"persist"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="vat_id", referencedColumnName="vat_id", nullable=false)
     * })
     * @Annotation\Type("DoctrineORMModule\Form\Element\EntitySelect")
     * @Annotation\Options({
     *      "target_class":"General\Entity\Vat",
     *      "find_method":{
     *          "name":"findBy",
     *          "params": {
     *              "criteria":{},
     *              "orderBy":{
     *                  "code":"ASC"}
     *              }
     *          }
     *      }
     * )
     * @Annotation\Attributes({"label":"txt-vat"})
     *
     * @var \General\Entity\Vat
     */
    private $vat;
    /**
     * @ORM\OneToMany(targetEntity="Invoice\Entity\Invoice", cascade={"persist"}, mappedBy="vatType")
     * @Annotation\Exclude()
     *
     * @var \Invoice\Entity\Invoice[]
     */
    private $invoice;
    /**
     * @ORM\OneToMany(targetEntity="Invoice\Entity\Vat\Dimension", cascade={"persist"}, mappedBy="vatType")
     * @Annotation\Exclude()
     *
     * @var \Invoice\Entity\Vat\Dimension[]|Collections\ArrayCollection
     */
    private $dimension;
    /**
     * @ORM\ManyToMany(targetEntity="Organisation\Entity\Financial", cascade={"persist"}, mappedBy="vatType")
     * @Annotation\Exclude()
     *
     * @var \Organisation\Entity\Financial[]|Collections\ArrayCollection
     */
    private $organisationFinancial;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->invoice               = new Collections\ArrayCollection();
        $this->dimension             = new Collections\ArrayCollection();
        $this->organisationFinancial = new Collections\ArrayCollection();
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
        return (string)$this->type;
    }

    /**
     * Returns the string identifier of the Resource.
     *
     * @return string
     */
    public function getResourceId()
    {
        return __NAMESPACE__ . ':' . __CLASS__ . ':' . $this->id;
    }

    /**
     * Set input filter.
     *
     * @param  InputFilterInterface $inputFilter
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
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name'       => 'type',
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
                                    'max'      => 30,
                                ],
                            ],
                        ],
                    ]
                )
            );
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name'       => 'description',
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
                                    'max'      => 64,
                                ],
                            ],
                        ],
                    ]
                )
            );
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name'     => 'vat',
                        'required' => true,
                    ]
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
     * Needed for the hydration of form elements.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'type'        => $this->type,
            'description' => $this->description,
            'vat'         => $this->vat,
        ];
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
     * @return \Invoice\Entity\Invoice[]
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param \Invoice\Entity\Invoice[] $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \General\Entity\Vat
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param \General\Entity\Vat $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @return Collections\ArrayCollection|\Invoice\Entity\Vat\Dimension[]
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * @param Collections\ArrayCollection|\Invoice\Entity\Vat\Dimension[] $dimension
     */
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;
    }

    /**
     * @return Collections\ArrayCollection|\Organisation\Entity\Financial[]
     */
    public function getOrganisationFinancial()
    {
        return $this->organisationFinancial;
    }

    /**
     * @param Collections\ArrayCollection|\Organisation\Entity\Financial[] $organisationFinancial
     */
    public function setOrganisationFinancial($organisationFinancial)
    {
        $this->organisationFinancial = $organisationFinancial;
    }
}
