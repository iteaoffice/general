<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Entity\Challenge;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use General\Entity\AbstractEntity;
use General\Entity\Challenge;

/**
 * @ORM\Table(name="challenge_pdf")
 * @ORM\Entity
 */
class Pdf extends AbstractEntity
{
    /**
     * @ORM\Column(name="pdf_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var int
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
     * @var DateTime
     */
    private $dateCreated;
    /**
     * @ORM\Column(name="date_end", type="datetime", nullable=true)
     *
     * @var DateTime
     */
    private $dateEnd;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Challenge", inversedBy="pdf", cascade={"persist"})
     * @ORM\JoinColumn(name="challenge_id", referencedColumnName="challenge_id", nullable=false)
     *
     * @var Challenge
     */
    private $challenge;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Pdf
    {
        $this->id = $id;
        return $this;
    }

    public function getPdf()
    {
        return $this->pdf;
    }

    public function setPdf($pdf): Pdf
    {
        $this->pdf = $pdf;
        return $this;
    }

    public function getDateCreated(): ?DateTime
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?DateTime $dateCreated): Pdf
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function getDateEnd(): ?DateTime
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?DateTime $dateEnd): Pdf
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): Pdf
    {
        $this->challenge = $challenge;
        return $this;
    }
}
