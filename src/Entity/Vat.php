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

use DateTime;
use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Event\Entity\DeskCosts;
use Event\Entity\Meeting\OptionCost;
use Invoice\Entity\Row;
use Invoice\Entity\Vat\Dimension;
use Zend\Form\Annotation;

/**
 * Entity for the General.
 *
 * @ORM\Table(name="vat")
 * @ORM\Entity(repositoryClass="General\Repository\Vat")
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("vat")
 *
 * @category General
 */
class Vat extends AbstractEntity
{
    public const VAT_VH = 7;
    /**
     * @ORM\Column(name="vat_id",type="integer",options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="vat_code",type="string",nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-vat-code"})
     *
     * @var string
     */
    private $code;
    /**
     * @ORM\Column(name="vat_percentage",type="decimal", precision=10, scale=2,nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-percentage"})
     *
     * @var float
     */
    private $percentage;
    /**
     * @ORM\Column(name="vat_date_start",type="datetime",nullable=false)
     * @Annotation\Type("\Zend\Form\Element\DateTime")
     * @Annotation\Options({"label":"txt-date-start"})
     *
     * @var DateTime
     */
    private $dateStart;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\VatType", cascade={"persist"}, mappedBy="vat")
     * @Annotation\Exclude()
     *
     * @var VatType[]
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
     *          "name":"findForForm",
     *          "params": {
     *              "criteria":{},
     *              "orderBy":{
     *                  "country":"ASC"}
     *              }
     *          }
     *      }
     * )
     * @Annotation\Attributes({"label":"txt-country"})
     *
     * @var Country
     */
    private $country;
    /**
     * @ORM\OneToMany(targetEntity="\Invoice\Entity\Row", cascade={"persist"}, mappedBy="vat")
     * @Annotation\Exclude()
     *
     * @var Row[]
     */
    private $invoiceRow;
    /**
     * @ORM\OneToMany(targetEntity="Event\Entity\DeskCosts", cascade={"persist"}, mappedBy="vat")
     * @Annotation\Exclude()
     *
     * @var DeskCosts[]
     */
    private $deskCosts;
    /**
     * @ORM\OneToMany(targetEntity="Event\Entity\Meeting\OptionCost", cascade={"persist"}, mappedBy="vat")
     * @Annotation\Exclude()
     *
     * @var OptionCost[]
     */
    private $optionCost;
    /**
     * @ORM\OneToMany(targetEntity="Invoice\Entity\Vat\Dimension", cascade={"persist"}, mappedBy="vat")
     * @Annotation\Exclude()
     *
     * @var Dimension[]|Collections\ArrayCollection
     */
    private $dimension;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->type = new Collections\ArrayCollection();
        $this->invoiceRow = new Collections\ArrayCollection();
        $this->deskCosts = new Collections\ArrayCollection();
        $this->optionCost = new Collections\ArrayCollection();
        $this->dimension = new Collections\ArrayCollection();
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
     * toString returns the name.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)sprintf("%s (%s %%)", $this->code, $this->percentage);
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param DateTime $dateStart
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
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
     * @return float
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param float $percentage
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * @return VatType[]
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param VatType[] $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Row[]
     */
    public function getInvoiceRow()
    {
        return $this->invoiceRow;
    }

    /**
     * @param Row[] $invoiceRow
     */
    public function setInvoiceRow($invoiceRow)
    {
        $this->invoiceRow = $invoiceRow;
    }

    /**
     * @return DeskCosts[]
     */
    public function getDeskCosts()
    {
        return $this->deskCosts;
    }

    /**
     * @param DeskCosts[] $deskCosts
     */
    public function setDeskCosts($deskCosts)
    {
        $this->deskCosts = $deskCosts;
    }

    /**
     * @return OptionCost[]
     */
    public function getOptionCost()
    {
        return $this->optionCost;
    }

    /**
     * @param OptionCost[] $optionCost
     */
    public function setOptionCost($optionCost)
    {
        $this->optionCost = $optionCost;
    }

    /**
     * @return Collections\ArrayCollection|Dimension[]
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * @param Collections\ArrayCollection|Dimension[] $dimension
     */
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;
    }
}
