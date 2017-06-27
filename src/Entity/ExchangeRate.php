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
use Project\Entity\EntityAbstract;

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
     *
     * @var integer
     */
    private $id;
    /**
     * @ORM\Column(name="rate", type="decimal", nullable=false)
     *
     * @var float
     */
    private $costs;
    /**
     * @ORM\Column(name="date", type="date", nullable=false)
     *
     * @var \DateTime
     */
    private $date;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Currency", cascade={"persist"}, inversedBy="exchangeRate")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="currency_id")
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
    public function getId(): int
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
    public function getCosts(): float
    {
        return $this->costs;
    }

    /**
     * @param float $costs
     * @return ExchangeRate
     */
    public function setCosts(float $costs): ExchangeRate
    {
        $this->costs = $costs;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
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
    public function getCurrency(): Currency
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
