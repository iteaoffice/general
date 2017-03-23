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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Web.
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
     *
     * @var integer
     */
    private $id;
    /**
     * @ORM\Column(name="web", type="string", length=30, nullable=false)
     *
     * @var string
     */
    private $web;
    /**
     * @ORM\Column(name="url", type="string", length=60, nullable=false)
     *
     * @var string
     */
    private $url;
    /**
     * @ORM\Column(name="mount", type="string", nullable=true)
     *
     * @var string
     */
    private $mount;
    /**
     * @ORM\OneToMany(targetEntity="Deeplink\Entity\Target", cascade={"persist"}, mappedBy="web")
     * @Annotation\Exclude()
     *
     * @var \Deeplink\Entity\Target[]|ArrayCollection
     */
    private $target;
    /**
     * @ORM\OneToMany(targetEntity="Admin\Entity\Session", cascade={"persist"}, mappedBy="web")
     * @Annotation\Exclude()
     *
     * @var \Admin\Entity\Session[]|ArrayCollection
     */
    private $session;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->session = new ArrayCollection();
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
     * @param $property
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getMount()
    {
        return $this->mount;
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
    public function getUrl()
    {
        return $this->url;
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
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @param string $web
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }

    /**
     * @return \Deeplink\Entity\Target[]
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param \Deeplink\Entity\Target[] $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
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
