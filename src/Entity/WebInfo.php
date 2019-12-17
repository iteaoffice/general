<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mailing\Entity\Sender;
use Mailing\Entity\Template;
use Zend\Form\Annotation;

/**
 * @ORM\Table(name="web_info")
 * @ORM\Entity(repositoryClass="General\Repository\WebInfo")
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("web_info")
 */
class WebInfo extends AbstractEntity
{
    public const PLAIN = 1;
    public const NOT_PLAIN = 0;

    protected static array $plainTemplates
        = [
            self::PLAIN => 'txt-plain',
            self::NOT_PLAIN => 'txt-not-plain',
        ];

    /**
     * @ORM\Column(name="info_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Type("\Zend\Form\Element\Hidden")
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="info", type="string", length=64, nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Attributes({"label":"txt-web-info-info-label","placeholder":"txt-web-info-info-placeholder"})
     * @Annotation\Options({"help-block":"txt-web-info-info-help-block"})
     *
     * @var string
     */
    private $info;
    /**
     * @ORM\Column(name="plain", type="smallint", nullable=false)
     * @Annotation\Exclude()
     * @var int
     */
    private $plain;
    /**
     * @ORM\Column(name="subject", type="string", nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-web-info-subject-label","help-block":"txt-web-info-subject-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-web-info-subject-placeholder"})
     *
     * @var string
     */
    private $subject;
    /**
     * @ORM\Column(name="content", type="text", nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Textarea")
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
     *              "criteria":{"personal":0},
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

    public function __construct()
    {
        $this->plain = self::NOT_PLAIN;
    }

    public static function getPlainTemplates(): array
    {
        return self::$plainTemplates;
    }

    public function __toString(): string
    {
        return (string)$this->info;
    }


    public function getPlain(bool $textual = false)
    {
        if ($textual) {
            return self::$plainTemplates[$this->plain];
        }

        return $this->plain;
    }

    public function setPlain($plain)
    {
        $this->plain = $plain;

        return $this;
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
}
