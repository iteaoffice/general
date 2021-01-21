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

use Application\Twig\TemplateInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Laminas\Form\Annotation;
use Mailing\Entity\Sender;
use Mailing\Entity\Template;

/**
 * @ORM\Table(name="web_info")
 * @ORM\Entity(repositoryClass="General\Repository\WebInfo")
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectPropertyHydrator")
 * @Annotation\Name("web_info")
 */
class WebInfo extends AbstractEntity implements TemplateInterface
{
    /**
     * @ORM\Column(name="info_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Type("\Laminas\Form\Element\Hidden")
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="date_created", type="datetime",nullable=false)
     * @Gedmo\Timestampable(on="create")
     * @Annotation\Exclude()
     *
     * @var DateTime
     */
    private $dateCreated;
    /**
     * @ORM\Column(name="last_update", type="datetime",nullable=true)
     * @Gedmo\Timestampable(on="update")
     * @Annotation\Exclude()
     *
     * @var DateTime
     */
    private $lastUpdate;
    /**
     * @ORM\Column(name="info", type="string", length=64, nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Attributes({"label":"txt-web-info-info-label","placeholder":"txt-web-info-info-placeholder"})
     * @Annotation\Options({"help-block":"txt-web-info-info-help-block"})
     *
     * @var string
     */
    private $info;
    /**
     * @ORM\Column(name="subject", type="string", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-web-info-subject-label","help-block":"txt-web-info-subject-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-web-info-subject-placeholder"})
     *
     * @var string
     */
    private $subject;
    /**
     * @ORM\Column(name="content", type="text", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-web-info-content-label","help-block":"txt-web-info-content-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-web-info-content-placeholder","id":"html_content"})
     *
     * @var string
     */
    private $content;
    /**
     * @ORM\ManyToOne(targetEntity="Mailing\Entity\Sender", cascade={"persist"}, inversedBy="webInfo")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="sender_id")
     * @Annotation\Type("DoctrineORMModule\Form\Element\EntitySelect")
     * @Annotation\Options({
     *      "target_class":"Mailing\Entity\Sender",
     *      "find_method":{
     *          "name":"findBy",
     *          "params": {
     *              "criteria":{},
     *              "orderBy":{
     *                  "sender":"ASC"}
     *              }
     *          }
     *      }
     * )
     * @Annotation\Options({"label":"txt-web-info-sender-label", "help-block":"txt-web-info-sender-help-block"})
     * @var Sender
     */
    private $sender;
    /**
     * @ORM\ManyToOne(targetEntity="Mailing\Entity\Template", cascade={"persist"}, inversedBy="webInfo")
     * @ORM\JoinColumn(name="mailtemplate_id", referencedColumnName="mailtemplate_id", nullable=false)
     * @Annotation\Type("DoctrineORMModule\Form\Element\EntitySelect")
     * @Annotation\Options({
     *      "target_class":"Mailing\Entity\Template",
     *      "find_method":{
     *          "name":"findBy",
     *          "params": {
     *              "criteria":{},
     *              "orderBy":{
     *                  "template":"ASC"}
     *              }
     *          }
     *      }
     * )
     * @Annotation\Options({"label":"txt-web-info-template-label", "help-block":"txt-web-info-template-help-block"})
     * @var Template
     */
    private $template;

    public function parseSourceContent(): string
    {
        return $this->content;
    }

    public function parseName(): string
    {
        return $this->info;
    }

    public function getLastUpdate(): ?DateTime
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(?DateTime $lastUpdate): WebInfo
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->info;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): WebInfo
    {
        $this->id = $id;
        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(string $info): WebInfo
    {
        $this->info = $info;
        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): WebInfo
    {
        $this->subject = $subject;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): WebInfo
    {
        $this->content = $content;
        return $this;
    }

    public function getSender(): ?Sender
    {
        return $this->sender;
    }

    public function setSender(Sender $sender): WebInfo
    {
        $this->sender = $sender;
        return $this;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(Template $template): WebInfo
    {
        $this->template = $template;
        return $this;
    }

    public function getDateCreated(): ?DateTime
    {
        return $this->dateCreated;
    }

    public function setDateCreated(?DateTime $dateCreated): WebInfo
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }
}
