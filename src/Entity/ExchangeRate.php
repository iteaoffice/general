<?php
/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Entity;

use Affiliation\Entity\Invoice;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Laminas\Form\Annotation;

/**
 * CostsVersion.
 *
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

    /**
     * @param int $id
     *
     * @return ExchangeRate
     */
    public function setId(int $id): ExchangeRate
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     *
     * @return ExchangeRate
     */
    public function setRate(float $rate): ExchangeRate
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     *
     * @return ExchangeRate
     */
    public function setDate(DateTime $date): ExchangeRate
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     *
     * @return ExchangeRate
     */
    public function setCurrency(Currency $currency): ExchangeRate
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Invoice[]|ArrayCollection
     */
    public function getAffiliationInvoice()
    {
        return $this->affiliationInvoice;
    }

    /**
     * @param Invoice[]|ArrayCollection $affiliationInvoice
     *
     * @return ExchangeRate
     */
    public function setAffiliationInvoice($affiliationInvoice): ExchangeRate
    {
        $this->affiliationInvoice = $affiliationInvoice;

        return $this;
    }
}
