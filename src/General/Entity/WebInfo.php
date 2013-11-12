<?php
/**
 * Debranova copyright message placeholder
 *
 * @category    Application
 * @package     Entity
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Debranova
 */
namespace General\Entity;

use Zend\Form\Annotation;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Doctrine\ORM\Mapping as ORM;


/**
 * WebInfo
 *
 * @ORM\Table(name="web_info")
 * @ORM\Entity
 */
class WebInfo extends EntityAbstract
{
    /**
     * @ORM\Column(name="info_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var integer
     */
    private $id;
    /**
     * @ORM\Column(name="info", type="string", length=64, nullable=false)
     * @var string
     */
    private $info;
    /**
     * @ORM\Column(name="plain", type="boolean", nullable=false)
     * @var boolean
     */
    private $plain;
    /**
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     * @var string
     */
    private $subject;
    /**
     * @ORM\Column(name="content", type="text", nullable=true)
     * @var string
     */
    private $content;
    /**
     * @ORM\Column(name="sync", type="smallint", nullable=false)
     * @var integer
     */
    private $sync;
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Web", cascade={"persist"}, inversedBy="webInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="web_id", referencedColumnName="web_id", nullable=false)
     * })
     * @var \General\Entity\Web
     */
    private $web;

    /**
     * Magic Getter
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
     * Magic Setter
     *
     * @param $property
     * @param $value
     *
     * @return void
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * @param InputFilterInterface $inputFilter
     *
     * @return void|InputFilterAwareInterface
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Setting an inputFilter is currently not supported");
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        return new InputFilter();
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param boolean $plain
     */
    public function setPlain($plain)
    {
        $this->plain = $plain;
    }

    /**
     * @return boolean
     */
    public function getPlain()
    {
        return $this->plain;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param int $sync
     */
    public function setSync($sync)
    {
        $this->sync = $sync;
    }

    /**
     * @return int
     */
    public function getSync()
    {
        return $this->sync;
    }

    /**
     * @param \General\Entity\Web $web
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }

    /**
     * @return \General\Entity\Web
     */
    public function getWeb()
    {
        return $this->web;
    }
}
