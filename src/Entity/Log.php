<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity for the General.
 *
 * @ORM\Table(name="log")
 * @ORM\Entity(repositoryClass="General\Repository\Log")
 */
class Log extends EntityAbstract
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
     * @ORM\Column(name="error_type", type="string", length=255, nullable=false)
     * @var string
     */
    private $errorType;
    /**
     * @ORM\Column(name="trace", type="text",nullable=true)
     * @var string|null
     */
    private $trace;
    /**
     * @ORM\Column(name="request_data", type="text",nullable=true)
     * @var string|null
     */
    private $requestData;


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
     *
     * @return void;
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
     * @return string
     */
    public function __toString(): string
    {
        return $this->event;
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
     *
     * @return Log
     */
    public function setId(int $id): Log
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @param int $date
     *
     * @return Log
     */
    public function setDate(int $date): Log
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return Log
     */
    public function setType(int $type): Log
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @param string $event
     *
     * @return Log
     */
    public function setEvent(string $event): Log
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Log
     */
    public function setUrl(string $url): Log
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @param string $file
     *
     * @return Log
     */
    public function setFile(string $file): Log
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @param int $line
     *
     * @return Log
     */
    public function setLine(int $line): Log
    {
        $this->line = $line;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorType(): string
    {
        return $this->errorType;
    }

    /**
     * @param string $errorType
     *
     * @return Log
     */
    public function setErrorType(string $errorType): Log
    {
        $this->errorType = $errorType;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTrace(): ?string
    {
        return $this->trace;
    }

    /**
     * @param null|string $trace
     *
     * @return Log
     */
    public function setTrace(?string $trace): Log
    {
        $this->trace = $trace;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getRequestData(): ?string
    {
        return $this->requestData;
    }

    /**
     * @param null|string $requestData
     *
     * @return Log
     */
    public function setRequestData(?string $requestData): Log
    {
        $this->requestData = $requestData;
        return $this;
    }
}
