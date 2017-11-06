<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * CostsVersion.
 *
 * @ORM\Table(name="currency_exchange_rate")
 * @ORM\Entity
 */
class ExchangeRate extends EntityAbstract
{
    /**
     * @ORM\Column(name="exchange_rate_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var integer
     */
    private $id;
    /**
     * @ORM\Column(name="rate", type="decimal", nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-exchange-rate-rate-label","help-block":"txt-exchange-rate-rate-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-exchange-rate-rate-placeholder"})
     *
     * @var float
     */
    private $rate;
    /**
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Date")
     * @Annotation\Options({"label":"txt-exchagen-rate-date-label","help-block":"txt-exchange-rate-date-help-block"})
     *
     * @var \DateTime
     */
    private $date;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Currency", cascade={"persist"}, inversedBy="exchangeRate")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="currency_id")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Currency
     */
    private $currency;

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
     * @return bool
     */
    public function __isset($property)
    {
        return isset($this->$property);
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
     * @return ExchangeRate
     */
    public function setRate(float $rate): ExchangeRate
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return ExchangeRate
     */
    public function setDate(\DateTime $date): ExchangeRate
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
     * @return ExchangeRate
     */
    public function setCurrency(Currency $currency): ExchangeRate
    {
        $this->currency = $currency;

        return $this;
    }
}
