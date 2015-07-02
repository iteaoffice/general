<?php
/**
 * Debranova copyright message placeholder.
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 Debranova
 */

namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Zend\Form\Annotation;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * WebInfo.
 *
 * @ORM\Table(name="web_info")
 * @ORM\Entity(repositoryClass="General\Repository\WebInfo")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("content_stylesheet")
 */
class WebInfo extends EntityAbstract implements ResourceInterface
{
    const PLAIN = 1;
    const NOT_PLAIN = 0;
    const SYNC = 1;
    const NO_SYNC = 0;

    /**
     * @var array
     */
    protected static $plainTemplates = [
        self::PLAIN     => "txt-plain",
        self::NOT_PLAIN => "txt-not-plain"
    ];

    /**
     * @var array
     */
    protected static $syncTemplates = [
        self::SYNC    => "txt-sync",
        self::NO_SYNC => "txt-no-sync"
    ];

    /**
     * @ORM\Column(name="info_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     * @var integer
     */
    private $id;
    /**
     * @ORM\Column(name="info", type="string", length=64, nullable=false)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-web-info-key-label"})
     *
     * @var string
     */
    private $info;
    /**
     * @ORM\Column(name="plain", type="smallint", nullable=false)
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Attributes({"array":"plainTemplates"})
     * @Annotation\Options({"label":"txt-web-info-plain-label","help-block":"txt-web-info-plain-help-block"})
     * @var int
     */
    private $plain;
    /**
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-web-info-subject-label"})
     *
     * @var string
     */
    private $subject;
    /**
     * @ORM\Column(name="content", type="text", nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-web-info-content-label"})
     *
     * @var string
     */
    private $content;
    /**
     * @ORM\Column(name="sync", type="smallint", nullable=false)
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Attributes({"array":"syncTemplates"})
     * @Annotation\Options({"label":"txt-web-info-sync-label","help-block":"txt-web-info-sync-help-block"})
     * @var integer
     */
    private $sync;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Web", cascade={"persist"}, inversedBy="webInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="web_id", referencedColumnName="web_id", nullable=true)
     * })
     *
     * @Annotation\Exclude()
     * @var \General\Entity\Web
     */
    private $web;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->sync = 1;
        $this->plain = 1;
    }

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
     * @return string
     */
    public function __toString()
    {
        return (string)$this->info;
    }

    /**
     * @param InputFilterInterface $inputFilter
     *
     * @return void|InputFilterAwareInterface
     *
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Setting an inputFilter is currently not supported");
    }

    /**
     * @return \Zend\InputFilter\InputFilter|\Zend\InputFilter\InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name'     => 'key',
                        'required' => true,
                    ]
                )
            );
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name'     => 'subject',
                        'required' => true,
                    ]
                )
            );
            $inputFilter->add(
                $factory->createInput(
                    [
                        'name'     => 'content',
                        'required' => true,
                    ]
                )
            );

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    /**
     * @return array
     */
    public static function getPlainTemplates()
    {
        return self::$plainTemplates;
    }

    /**
     * @return array
     */
    public static function getSyncTemplates()
    {
        return self::$syncTemplates;
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
     * @return WebInfo
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @param bool $textual
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
     * @return WebInfo
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param bool $textual
     * @return int|string
     */
    public function getSync($textual = false)
    {
        if ($textual) {
            self::$syncTemplates[$this->sync];
        }

        return $this->sync;
    }

    /**
     * @param int $sync
     * @return WebInfo
     */
    public function setSync($sync)
    {
        $this->sync = $sync;

        return $this;
    }

    /**
     * @return Web
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @param Web $web
     * @return WebInfo
     */
    public function setWeb($web)
    {
        $this->web = $web;

        return $this;
    }


}
