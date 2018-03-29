<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Invoice
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/invoice for the canonical source repository
 */
declare(strict_types=1);

namespace General\Entity\Challenge;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use General\Entity\AbstractEntity;

/**
 * InvoicePdf.
 *
 * @ORM\Table(name="challenge_pdf")
 * @ORM\Entity
 */
class Pdf extends AbstractEntity
{
    /**
     * @ORM\Column(name="pdf_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer
     */
    private $id;
    /**
     * @ORM\Column(name="pdf", type="blob", nullable=true)
     *
     * @var resource
     */
    private $pdf;
    /**
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     *
     * @var \DateTime
     */
    private $dateCreated;
    /**
     * @ORM\Column(name="date_end", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $dateEnd;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge", inversedBy="pdf", cascade="persist")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="challenge_id", nullable=false)
     * })
     *
     * @var \General\Entity\Challenge
     */
    private $challenge;

    public function __construct()
    {
    }

    /**
     * @param $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Pdf
     */
    public function setId(int $id): Pdf
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return resource
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @param string $pdf
     *
     * @return Pdf
     */
    public function setPdf(string $pdf): Pdf
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     *
     * @return Pdf
     */
    public function setDateCreated(\DateTime $dateCreated): Pdf
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd(): \DateTime
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime $dateEnd
     *
     * @return Pdf
     */
    public function setDateEnd(\DateTime $dateEnd): Pdf
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * @return \General\Entity\Challenge
     */
    public function getChallenge(): \General\Entity\Challenge
    {
        return $this->challenge;
    }

    /**
     * @param \General\Entity\Challenge $challenge
     *
     * @return Pdf
     */
    public function setChallenge(\General\Entity\Challenge $challenge): Pdf
    {
        $this->challenge = $challenge;

        return $this;
    }
}
