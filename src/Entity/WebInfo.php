<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mailing\Entity\Sender;
use Zend\Form\Annotation;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * WebInfo.
 *
 * @ORM\Table(name="web_info")
 * @ORM\Entity(repositoryClass="General\Repository\WebInfo")
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("content_stylesheet")
 */
class WebInfo extends EntityAbstract implements ResourceInterface
{
    const PLAIN = 1;
    const NOT_PLAIN = 0;

    /**
     * @var array
     */
    protected static $plainTemplates
        = [
            self::PLAIN     => "txt-plain",
            self::NOT_PLAIN => "txt-not-plain",
        ];

    /**
     * @ORM\Column(name="info_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Type("\Zend\Form\Element\Hidden")
     * @var integer
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
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
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
     * @Annotation\Attributes({"placeholder":"txt-web-info-content-placeholder","rows":"20"})
     *
     * @var string
     */
    private $content;
    /**
     * @ORM\ManyToOne(targetEntity="Mailing\Entity\Sender", cascade={"persist"}, inversedBy="webInfo")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="sender_id")
     * })
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
     * @var \Mailing\Entity\Sender
     */
    private $sender;
    /**
     * @ORM\ManyToOne(targetEntity="Mailing\Entity\Template", cascade={"persist"}, inversedBy="webInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mailtemplate_id", referencedColumnName="mailtemplate_id", nullable=false)
     * })
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
     * @var \Mailing\Entity\Template
     */
    private $template;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->plain = self::NOT_PLAIN;
    }

    /**
     * @return array
     */
    public static function getPlainTemplates(): array
    {
        return self::$plainTemplates;
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
        return (string)$this->info;
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
     * @return WebInfo
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param string $info
     *
     * @return WebInfo
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @param bool $textual
     *
     * @return int|string
     */
    public function getPlain($textual = false)
    {
        if ($textual) {
            return self::$plainTemplates[$this->plain];
        }

        return $this->plain;
    }

    /**
     * @param int $plain
     *
     * @return WebInfo
     */
    public function setPlain($plain)
    {
        $this->plain = $plain;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return WebInfo
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return WebInfo
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Sender
     */
    public function getSender(): ?Sender
    {
        return $this->sender;
    }

    /**
     * @param Sender $sender
     * @return WebInfo
     */
    public function setSender(Sender $sender): WebInfo
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return \Mailing\Entity\Template
     */
    public function getTemplate(): ?\Mailing\Entity\Template
    {
        return $this->template;
    }

    /**
     * @param \Mailing\Entity\Template $template
     * @return WebInfo
     */
    public function setTemplate(\Mailing\Entity\Template $template): WebInfo
    {
        $this->template = $template;

        return $this;
    }
}
