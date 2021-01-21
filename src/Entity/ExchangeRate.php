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

use Affiliation\Entity\Invoice;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Laminas\Form\Annotation;

/**
 * @ORM\Table(name="currency_exchange_rate")
 * @ORM\Entity
 */
class ExchangeRate extends AbstractEntity
{
    /**
     * @ORM\Column(name="exchange_rate_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="rate", type="decimal", precision=10, scale=6, nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-exchange-rate-rate-label","help-block":"txt-exchange-rate-rate-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-exchange-rate-rate-placeholder"})
     *
     * @var float
     */
    private $rate;
    /**
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\Date")
     * @Annotation\Options({"label":"txt-exchange-rate-date-label","help-block":"txt-exchange-rate-date-help-block"})
     *
     * @var DateTime
     */
    private $date;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Currency", cascade={"persist"}, inversedBy="exchangeRate")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="currency_id")
     * @Annotation\Exclude()
     *
     * @var Currency
     */
    private $currency;
    /**
     * @ORM\OneToMany(targetEntity="Affiliation\Entity\Invoice", cascade={"persist","remove"}, mappedBy="exchangeRate")
     * @Annotation\Exclude()
     *
     * @var Invoice[]|ArrayCollection
     */
    private $affiliationInvoice;

    public function __construct()
    {
        $this->affiliationInvoice = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): ExchangeRate
    {
        $this->id = $id;

        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate): ExchangeRate
    {
        $this->rate = $rate;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): ExchangeRate
    {
        $this->date = $date;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): ExchangeRate
    {
        $this->currency = $currency;

        return $this;
    }


    public function getAffiliationInvoice()
    {
        return $this->affiliationInvoice;
    }

    public function setAffiliationInvoice($affiliationInvoice): ExchangeRate
    {
        $this->affiliationInvoice = $affiliationInvoice;

        return $this;
    }
}
