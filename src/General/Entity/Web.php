<?php
/**
 * Debranova copyright message placeholder
 *
 * @category  Application
 * @package   Entity
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 Debranova
 */
namespace General\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Web
 *
 * @ORM\Table(name="web")
 * @ORM\Entity
 */
class Web extends EntityAbstract
{
    /**
     * @ORM\Column(name="web_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var integer
     */
    private $id;
    /**
     * @ORM\Column(name="web", type="string", length=30, nullable=false)
     * @var string
     */
    private $web;
    /**
     * @ORM\Column(name="url", type="string", length=60, nullable=false)
     * @var string
     */
    private $url;
    /**
     * @ORM\Column(name="mount", type="string", length=40, nullable=true)
     * @var string
     */
    private $mount;
    /**
     * @ORM\OneToMany(targetEntity="Deeplink\Entity\Target", cascade={"persist"}, mappedBy="web")
     * @Annotation\Exclude()
     * @var \Deeplink\Entity\Target[]
     */
    private $target;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\WebInfo", cascade={"persist"}, mappedBy="web")
     * @Annotation\Exclude()
     * @var \General\Entity\WebInfo[]
     */
    private $webInfo;
    /**
     * @ORM\OneToMany(targetEntity="Admin\Entity\Session", cascade={"persist"}, mappedBy="web")
     * @Annotation\Exclude()
     * @var \General\Entity\WebInfo[]
     */
    private $session;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->webInfo = new ArrayCollection();
        $this->session = new ArrayCollection();
    }

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
     * @param string $mount
     */
    public function setMount($mount)
    {
        $this->mount = $mount;
    }

    /**
     * @return string
     */
    public function getMount()
    {
        return $this->mount;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $web
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }

    /**
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @param \Deeplink\Entity\Target[] $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return \Deeplink\Entity\Target[]
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param \General\Entity\WebInfo[] $webInfo
     */
    public function setWebInfo($webInfo)
    {
        $this->webInfo = $webInfo;
    }

    /**
     * @return \General\Entity\WebInfo[]
     */
    public function getWebInfo()
    {
        return $this->webInfo;
    }

    /**
     * @return \Admin\Entity\Session[]
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param \Admin\Entity\Session[] $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

}
