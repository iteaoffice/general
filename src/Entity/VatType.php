<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Invoice\Entity\Invoice;
use Invoice\Entity\Vat\Dimension;
use Laminas\Form\Annotation;
use Organisation\Entity\Financial;

/**
 * @ORM\Table(name="vat_type")
 * @ORM\Entity(repositoryClass="General\Repository\VatType")
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectPropertyHydrator")
 * @Annotation\Name("vat_type")
 */
class VatType extends AbstractEntity
{
    public const VAT_TYPE_LOCAL = 1;
    public const VAT_TYPE_IN_EU_SHIFT = 2;
    public const VAT_TYPE_IN_EU_NO_SHIFT = 3;
    public const VAT_TYPE_NON_EU = 4;

    /**
     * @ORM\Column(name="type_id",type="integer",options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="type",type="string",length=30, unique=true)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-vat-type"})
     *
     * @var string
     */
    private $type;
    /**
     * @ORM\Column(name="description",type="string",length=64)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-description"})
     *
     * @var string
     */
    private $description;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Vat", inversedBy="type", cascade={"persist"})
     * @ORM\JoinColumn(name="vat_id", referencedColumnName="vat_id", nullable=false)
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
     * @var Vat
     */
    private $vat;
    /**
     * @ORM\OneToMany(targetEntity="Invoice\Entity\Invoice", cascade={"persist"}, mappedBy="vatType")
     * @Annotation\Exclude()
     *
     * @var Invoice[]
     */
    private $invoice;
    /**
     * @ORM\OneToMany(targetEntity="Invoice\Entity\Vat\Dimension", cascade={"persist"}, mappedBy="vatType")
     * @Annotation\Exclude()
     *
     * @var Dimension[]|Collections\ArrayCollection
     */
    private $dimension;
    /**
     * @ORM\ManyToMany(targetEntity="Organisation\Entity\Financial", cascade={"persist"}, mappedBy="vatType")
     * @Annotation\Exclude()
     *
     * @var Financial[]|Collections\ArrayCollection
     */
    private $organisationFinancial;

    public function __construct()
    {
        $this->invoice = new Collections\ArrayCollection();
        $this->dimension = new Collections\ArrayCollection();
        $this->organisationFinancial = new Collections\ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): VatType
    {
        $this->id = $id;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): VatType
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): VatType
    {
        $this->description = $description;
        return $this;
    }

    public function getVat(): ?Vat
    {
        return $this->vat;
    }

    public function setVat(?Vat $vat): VatType
    {
        $this->vat = $vat;
        return $this;
    }

    public function getInvoice()
    {
        return $this->invoice;
    }

    public function setInvoice($invoice): VatType
    {
        $this->invoice = $invoice;
        return $this;
    }

    public function getDimension()
    {
        return $this->dimension;
    }

    public function setDimension($dimension): VatType
    {
        $this->dimension = $dimension;
        return $this;
    }

    public function getOrganisationFinancial()
    {
        return $this->organisationFinancial;
    }

    public function setOrganisationFinancial($organisationFinancial): VatType
    {
        $this->organisationFinancial = $organisationFinancial;
        return $this;
    }
}
