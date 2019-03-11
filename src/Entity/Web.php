<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Web.
 *
 * @ORM\Table(name="web")
 * @ORM\Entity
 */
class Web extends AbstractEntity
{
    /**
     * @ORM\Column(name="web_id", type="integer", options={"unsigned":true})
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
     * @ORM\Column(name="url", type="string", nullable=false)
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

    public function __construct()
    {
        $this->session = new ArrayCollection();
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function __isset($property)
    {
        return isset($this->$property);
    }

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

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

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
