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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Project\Entity\Contract;
use Laminas\Form\Annotation;

/**
 * @ORM\Table(name="currency")
 * @ORM\Entity(repositoryClass="General\Repository\Currency")
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectPropertyHydrator")
 * @Annotation\Name("currency")
 *
 * @category General
 */
class Currency extends AbstractEntity
{
    /**
     * @ORM\Column(name="currency_id",type="integer",options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="name",type="string",unique=true)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-currency-name-label","help-block":"txt-currency-name-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-currency-name-placeholder"})
     *
     * @var string
     */
    private $name;
    /**
     * @ORM\Column(name="iso4217",type="string",unique=true)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-currency-iso4217-label","help-block":"txt-currency-iso4217-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-currency-iso4217-placeholder"})
     *
     * @var string
     */
    private $iso4217;
    /**
     * @ORM\Column(name="symbol",type="string")
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-currency-symbol-label","help-block":"txt-currency-symbol-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-currency-symbol-placeholder"})
     *
     * @var string
     */
    private $symbol;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\ExchangeRate", cascade={"persist"}, mappedBy="currency")
     * @ORM\OrderBy({"date" = "DESC"})
     * @Annotation\Exclude()
     *
     * @var ExchangeRate[]|ArrayCollection
     */
    private $exchangeRate;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Contract", cascade={"persist"}, mappedBy="currency")
     * @Annotation\Exclude()
     *
     * @var Contract[]|ArrayCollection
     */
    private $contract;

    public function __construct()
    {
        $this->contract = new ArrayCollection();
        $this->exchangeRate = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Currency
     */
    public function setId($id): Currency
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Currency
     */
    public function setName(string $name): Currency
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     *
     * @return Currency
     */
    public function setSymbol(string $symbol): Currency
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return string
     */
    public function getIso4217(): ?string
    {
        return $this->iso4217;
    }

    /**
     * @param string $iso4217
     *
     * @return Currency
     */
    public function setIso4217(string $iso4217): Currency
    {
        $this->iso4217 = $iso4217;

        return $this;
    }

    /**
     * @return ArrayCollection|Contract[]
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param ArrayCollection|Contract[] $contract
     *
     * @return Currency
     */
    public function setContract($contract)
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * @return ArrayCollection|ExchangeRate[]
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

    /**
     * @param ArrayCollection|ExchangeRate[] $exchangeRate
     *
     * @return Currency
     */
    public function setExchangeRate($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }
}
