<?php

/**
 * ITEA Office all rights reserved
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log")
 * @ORM\Entity(repositoryClass="General\Repository\Log")
 */
class Log extends AbstractEntity
{
    /**
     * @ORM\Column(type="integer",nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="date", type="string",nullable=false)
     * @var int
     */
    private $date;
    /**
     * @ORM\Column(name="type", type="integer",nullable=false)
     * @var int
     */
    private $type;
    /**
     * @ORM\Column(name="event", type="text",nullable=false)
     * @var string
     */
    private $event;
    /**
     * @ORM\Column(name="url", type="string", length=2000, nullable=false)
     * @var string
     */
    private $url;
    /**
     * @ORM\Column(name="file", type="string", length=2000, nullable=false)
     * @var string
     */
    private $file;
    /**
     * @ORM\Column(name="line", type="integer",nullable=false)
     * @var int
     */
    private $line;
    /**
     * @ORM\Column(name="error_type", type="string", nullable=false)
     * @var string
     */
    private $errorType;
    /**
     * @ORM\Column(name="trace", type="text",nullable=true)
     * @var string
     */
    private $trace;
    /**
     * @ORM\Column(name="request_data", type="text",nullable=true)
     * @var string
     */
    private $requestData;

    public function __toString(): string
    {
        return $this->event;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Log
    {
        $this->id = $id;
        return $this;
    }

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function setDate(?int $date): Log
    {
        $this->date = $date;
        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): Log
    {
        $this->type = $type;
        return $this;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function setEvent(?string $event): Log
    {
        $this->event = $event;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): Log
    {
        $this->url = $url;
        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): Log
    {
        $this->file = $file;
        return $this;
    }

    public function getLine(): ?int
    {
        return $this->line;
    }

    public function setLine(?int $line): Log
    {
        $this->line = $line;
        return $this;
    }

    public function getErrorType(): ?string
    {
        return $this->errorType;
    }

    public function setErrorType(?string $errorType): Log
    {
        $this->errorType = $errorType;
        return $this;
    }

    public function getTrace(): ?string
    {
        return $this->trace;
    }

    public function setTrace(?string $trace): Log
    {
        $this->trace = $trace;
        return $this;
    }

    public function getRequestData(): ?string
    {
        return $this->requestData;
    }

    public function setRequestData(?string $requestData): Log
    {
        $this->requestData = $requestData;
        return $this;
    }
}
