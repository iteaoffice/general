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
 * Entity for the General
 *
 * @ORM\Table(name="vat")
 * @ORM\Entity
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("vat")
 *
 * @category    General
 * @package     Entity
 */
class Vat extends EntityAbstract implements ResourceInterface
{
    /**
     * @ORM\Column(name="vat_id",type="integer",nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="vat_code",type="string",length=45,nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-vat-code"})
     * @var string
     */
    private $code;
    /**
     * @ORM\Column(name="vat_percentage",type="decimal",nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-percentage"})
     * @var float
     */
    private $percentage;
    /**
     * @ORM\Column(name="vat_date_start",type="datetime",nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-date-start"})
     * @var \DateTime
     */
    private $dateStart;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\VatType", cascade={"persist"}, mappedBy="vat")
     * @Annotation\Exclude()
     * @var \General\Entity\VatType[]
     */
    private $type;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Country", inversedBy="vat", cascade={"persist"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id", nullable=true)
     * })
     * @Annotation\Type("DoctrineORMModule\Form\Element\EntitySelect")
     * @Annotation\Options({
     *      "target_class":"General\Entity\Country",
     *      "find_method":{
     *          "name":"findBy",
     *          "params": {
     *              "criteria":{},
     *              "orderBy":{
     *                  "country":"ASC"}
     *              }
     *          }
     *      }
     * )
     * @Annotation\Attributes({"label":"txt-country"})
     * @var \General\Entity\Country
     */
    private $country;
    /**
     * @ORM\OneToMany(targetEntity="\Invoice\Entity\Row", cascade={"persist"}, mappedBy="vat")
     * @Annotation\Exclude()
     * @var \Invoice\Entity\Row[]
     */
    private $invoiceRow;
    /**
     * @ORM\OneToMany(targetEntity="\Invoice\Entity\FinancialRow", cascade={"persist"}, mappedBy="vat")
     * @Annotation\Exclude()
     * @var \Invoice\Entity\FinancialRow[]
     */
    private $financialRow;
    /**
     * @ORM\OneToMany(targetEntity="Event\Entity\DeskCosts", cascade={"persist"}, mappedBy="vat")
     * @Annotation\Exclude()
     * @var \Event\Entity\DeskCosts[]
     */
    private $deskCosts;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->type         = new Collections\ArrayCollection();
        $this->invoiceRow   = new Collections\ArrayCollection();
        $this->financialRow = new Collections\ArrayCollection();
        $this->deskCosts    = new Collections\ArrayCollection();
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
        return (string)$this->percentage . '%';
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
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'     => 'code',
                        'required' => true,
                    )
                )
            );


            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'     => 'percentage',
                        'required' => true,
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'     => 'dateStart',
                        'required' => true,
                    )
                )
            );


            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'     => 'type',
                        'required' => true,
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'     => 'country',
                        'required' => false,
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
            'code'       => $this->code,
            'percentage' => $this->percentage,
            'dateStart'  => $this->dateStart,
            'type'       => $this->type,
            'country'    => $this->country
        );
    }

    public function populate()
    {
        return $this->getArrayCopy();
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param \General\Entity\Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return \General\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param \DateTime $dateStart
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
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
     * @param float $percentage
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * @return float
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param \General\Entity\VatType[] $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \General\Entity\VatType[]
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \Invoice\Entity\Row[] $invoiceRow
     */
    public function setInvoiceRow($invoiceRow)
    {
        $this->invoiceRow = $invoiceRow;
    }

    /**
     * @return \Invoice\Entity\Row[]
     */
    public function getInvoiceRow()
    {
        return $this->invoiceRow;
    }

    /**
     * @param \Invoice\Entity\FinancialRow[] $financialRow
     */
    public function setFinancialRow($financialRow)
    {
        $this->financialRow = $financialRow;
    }

    /**
     * @return \Invoice\Entity\FinancialRow[]
     */
    public function getFinancialRow()
    {
        return $this->financialRow;
    }

    /**
     * @param \Event\Entity\DeskCosts[] $deskCosts
     */
    public function setDeskCosts($deskCosts)
    {
        $this->deskCosts = $deskCosts;
    }

    /**
     * @return \Event\Entity\DeskCosts[]
     */
    public function getDeskCosts()
    {
        return $this->deskCosts;
    }
}
