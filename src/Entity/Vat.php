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
use Laminas\Form\Annotation;

/**
 * Entity for the General.
 *
 * @ORM\Table(name="vat")
 * @ORM\Entity(repositoryClass="General\Repository\Vat")
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectProperty")
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
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-vat-code"})
     *
     * @var string
     */
    private $code;
    /**
     * @ORM\Column(name="vat_percentage",type="decimal", precision=10, scale=2,nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-percentage"})
     *
     * @var float
     */
    private $percentage;
    /**
     * @ORM\Column(name="vat_date_start",type="datetime",nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\DateTime")
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
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id", nullable=true)
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

    public function __construct()
    {
        $this->type = new Collections\ArrayCollection();
        $this->invoiceRow = new Collections\ArrayCollection();
        $this->deskCosts = new Collections\ArrayCollection();
        $this->optionCost = new Collections\ArrayCollection();
        $this->dimension = new Collections\ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)sprintf('%s (%s %%)', $this->code, $this->percentage);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Vat
    {
        $this->id = $id;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code): Vat
    {
        $this->code = $code;
        return $this;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setPercentage($percentage): Vat
    {
        $this->percentage = $percentage;
        return $this;
    }

    public function getDateStart(): ?DateTime
    {
        return $this->dateStart;
    }

    public function setDateStart(?DateTime $dateStart): Vat
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): Vat
    {
        $this->type = $type;
        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): Vat
    {
        $this->country = $country;
        return $this;
    }

    public function getInvoiceRow()
    {
        return $this->invoiceRow;
    }

    public function setInvoiceRow($invoiceRow): Vat
    {
        $this->invoiceRow = $invoiceRow;
        return $this;
    }

    public function getDeskCosts()
    {
        return $this->deskCosts;
    }

    public function setDeskCosts($deskCosts): Vat
    {
        $this->deskCosts = $deskCosts;
        return $this;
    }

    public function getOptionCost()
    {
        return $this->optionCost;
    }

    public function setOptionCost($optionCost): Vat
    {
        $this->optionCost = $optionCost;
        return $this;
    }

    public function getDimension()
    {
        return $this->dimension;
    }

    public function setDimension($dimension): Vat
    {
        $this->dimension = $dimension;
        return $this;
    }
}
