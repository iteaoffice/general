<?php

/**
 * ITEA Office copyright message placeholder.
 *
 * @category   Project
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\View\Helper;

use BjyAuthorize\Controller\Plugin\IsAllowed;
use BjyAuthorize\Service\Authorize;
use General\Acl\Assertion\AssertionAbstract;
use General\Entity\ContentType;
use General\Entity\Country;
use General\Entity\EntityAbstract;
use General\Entity\WebInfo;
use Zend\View\Helper\ServerUrl;
use Zend\View\Helper\Url;

/**
 * Class LinkAbstract.
 */
abstract class LinkAbstract extends AbstractViewHelper
{
    /**
     * @var string Text to be placed as title or as part of the linkContent
     */
    protected $text;
    /**
     * @var string
     */
    protected $router;
    /**
     * @var string
     */
    protected $action;
    /**
     * @var string
     */
    protected $show;
    /**
     * @var WebInfo
     */
    protected $webInfo;
    /**
     * @var ContentType
     */
    protected $contentType;
    /**
     * @var Country
     */
    protected $country;
    /**
     * @var string
     */
    protected $alternativeShow;
    /**
     * @var array List of parameters needed to construct the URL from the router
     */
    protected $routerParams = [];
    /**
     * @var array content of the link (will be imploded during creation of the link)
     */
    protected $linkContent = [];
    /**
     * @var array Classes to be given to the link
     */
    protected $classes = [];
    /**
     * @var array
     */
    protected $showOptions = [];

    /**
     * This function produces the link in the end.
     *
     * @return string
     */
    public function createLink()
    {
        /**
         * @var $url Url
         */
        $url = $this->getHelperPluginManager()->get('url');
        /**
         * @var $serverUrl ServerUrl
         */
        $serverUrl = $this->getHelperPluginManager()->get('serverUrl');
        $this->linkContent = [];
        $this->classes = [];
        $this->parseAction();
        $this->parseShow();
        if ('social' === $this->getShow()) {
            return $serverUrl->__invoke() . $url($this->router, $this->routerParams);
        }
        $uri = '<a href="%s" title="%s" class="%s">%s</a>';

        return sprintf(
            $uri,
            $serverUrl() . $url($this->router, $this->routerParams),
            htmlentities($this->text),
            implode(' ', $this->classes),
            in_array($this->getShow(), ['icon', 'button', 'flag', 'alternativeShow']) ? implode('', $this->linkContent)
            : htmlentities(implode('', $this->linkContent))
        );
    }

    /**
     *
     */
    public function parseAction()
    {
        return $this->action = null;
    }

    /**
     * @throws \Exception
     */
    public function parseShow()
    {
        switch ($this->getShow()) {
            case 'button':
            case 'icon':
                switch ($this->getAction()) {
                    case 'new':
                        $this->addLinkContent('<i class="fa fa-plus"></i>');
                        break;
                    case 'list':
                    case 'list-admin':
                        $this->addLinkContent('<i class="fa fa-list-ul"></i>');
                        break;
                    case 'delete':
                        $this->addLinkContent('<i class="fa fa-trash-o"></i>');
                        break;
                    case 'edit':
                        $this->addLinkContent('<i class="fa fa-edit"></i>');
                        break;
                    case 'view':
                        $this->addLinkContent('<i class="fa fa-external-link"></i>');
                        break;
                    default:
                        $this->addLinkContent('<i class="fa fa-file-o"></i>');
                        break;
                }

                if ($this->getShow() === 'button') {
                    $this->addLinkContent(' ' . $this->getText());
                    if ($this->getAction() === 'delete') {
                        $this->addClasses("btn btn-danger");
                    } else {
                        $this->addClasses("btn btn-primary");
                    }
                }
                break;
            case 'text':
                $this->addLinkContent($this->getText());
                break;
            case 'paginator':
                if (is_null($this->getAlternativeShow())) {
                    throw new \InvalidArgumentException(sprintf("this->alternativeShow cannot be null for a paginator link"));
                }
                $this->addLinkContent($this->getAlternativeShow());
                break;
            case 'social':
                /*
                 * Social is treated in the createLink function, no content needs to be created
                 */

                return;
            default:
                if (!array_key_exists($this->getShow(), $this->showOptions)) {
                    throw new \InvalidArgumentException(sprintf(
                        "The option \"%s\" should be available in the showOptions array, only \"%s\" are available",
                        $this->getShow(),
                        implode(', ', array_keys($this->showOptions))
                    ));
                }
                $this->addLinkContent($this->showOptions[$this->getShow()]);
                break;
        }
    }

    /**
     * @return string
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * @param string $show
     */
    public function setShow($show)
    {
        $this->show = $show;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param $linkContent
     *
     * @return $this
     */
    public function addLinkContent($linkContent)
    {
        if (!is_array($linkContent)) {
            $linkContent = [$linkContent];
        }

        foreach ($linkContent as $content) {
            $this->linkContent[] = $content;
        }

        return $this;
    }

    /**
     * @param string $classes
     *
     * @return $this
     */
    public function addClasses($classes)
    {
        if (!is_array($classes)) {
            $classes = [$classes];
        }
        foreach ($classes as $class) {
            $this->classes[] = $class;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getAlternativeShow()
    {
        return $this->alternativeShow;
    }

    /**
     * @param string $alternativeShow
     */
    public function setAlternativeShow($alternativeShow)
    {
        $this->alternativeShow = $alternativeShow;
    }

    /**
     * Reset the params.
     */
    public function resetRouterParams()
    {
        $this->routerParams = [];
    }

    /**
     * @param $showOptions
     */
    public function setShowOptions($showOptions)
    {
        $this->showOptions = $showOptions;
    }

    /**
     * @param EntityAbstract $entity
     * @param string         $assertion
     * @param string         $action
     *
     * @return bool
     */
    public function hasAccess(EntityAbstract $entity, $assertion, $action)
    {
        $assertion = $this->getAssertion($assertion);
        if (!is_null($entity)
            && !$this->getAuthorizeService()->getAcl()->hasResource($entity)
        ) {
            $this->getAuthorizeService()->getAcl()->addResource($entity);
            $this->getAuthorizeService()->getAcl()->allow([], $entity, [], $assertion);
        }
        if (!$this->isAllowed($entity, $action)) {
            return false;
        }

        return true;
    }

    /**
     * @param $assertion
     *
     * @return AssertionAbstract
     */
    public function getAssertion($assertion)
    {
        return $this->getServiceManager()->get($assertion);
    }

    /**
     * @return Authorize
     */
    public function getAuthorizeService()
    {
        return $this->getServiceManager()->get('BjyAuthorize\Service\Authorize');
    }

    /**
     * @param null|EntityAbstract $resource
     * @param string              $privilege
     *
     * @return bool
     */
    public function isAllowed($resource, $privilege = null)
    {
        /**
         * @var $isAllowed IsAllowed
         */
        $isAllowed = $this->getHelperPluginManager()->get('isAllowed');

        return $isAllowed($resource, $privilege);
    }

    /**
     * Add a parameter to the list of parameters for the router.
     *
     * @param string $key
     * @param        $value
     * @param bool   $allowNull
     */
    public function addRouterParam($key, $value, $allowNull = true)
    {
        if (!$allowNull && is_null($value)) {
            throw new \InvalidArgumentException(sprintf("null is not allowed for %s", $key));
        }
        if (!is_null($value)) {
            $this->routerParams[$key] = $value;
        }
    }

    /**
     * @return string
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param string $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function getRouterParams()
    {
        return $this->routerParams;
    }

    /**
     * @param Country $country
     * @param int     $width
     *
     * @return string
     */
    public function getCountryFlag(Country $country, $width)
    {
        return $this->getHelperPluginManager()->get('countryFlag')->__invoke($country, $width);
    }

    /**
     * @return WebInfo
     */
    public function getWebInfo()
    {
        if (is_null($this->webInfo)) {
            $this->webInfo = new WebInfo();
        }

        return $this->webInfo;
    }

    /**
     * @param WebInfo $webInfo
     *
     * @return LinkAbstract
     */
    public function setWebInfo($webInfo)
    {
        $this->webInfo = $webInfo;

        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        if (is_null($this->country)) {
            $this->country = new Country();
        }

        return $this->country;
    }

    /**
     * @param Country $country
     *
     * @return LinkAbstract
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return ContentType
     */
    public function getContentType()
    {
        if (is_null($this->contentType)) {
            $this->contentType = new ContentType();
        }

        return $this->contentType;
    }

    /**
     * @param ContentType $contentType
     *
     * @return LinkAbstract
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }
}
