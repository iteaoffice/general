<?php

/**
 * ITEA Office copyright message placeholder.
 *
 * @category   Organisation
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\View\Helper;

use Zend\View\Helper\Url;

/**
 * Class LinkAbstract.
 */
abstract class ImageAbstract extends AbstractViewHelper
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
    protected $imageId;
    /**
     * @var array List of parameters needed to construct the URL from the router
     */
    protected $routerParams = [];
    /**
     * @var array Classes to be given to the link
     */
    protected $classes = [];
    /**
     * @var int width
     */
    protected $width = null;
    /**
     * @var bool
     */
    protected $lightBox = false;

    /**
     * This function produces the link in the end.
     *
     * @return string
     */
    public function createImageUrl()
    {
        /**
         * @var Url $url
         */
        $url = $this->getHelperPluginManager()->get('url');

        $imageUrl = '<img src="%s" id="%s" class="%s" %s>';

        $image = sprintf(
            $imageUrl,
            $url($this->router, $this->routerParams),
            $this->imageId,
            implode(' ', $this->classes),
            is_null($this->width) ? null : ' width="' . $this->width . '"'
        );

        if ( ! $this->lightBox) {
            return $image;
        } else {
            return '<a href="' . $url($this->router, $this->routerParams) . '" data-lightbox="itea">' . $image . '</a>';
        }
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
        if ( ! $allowNull && is_null($value)) {
            throw new \InvalidArgumentException(sprintf("null is not allowed for %s", $key));
        }
        if ( ! is_null($value)) {
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
     * @return string
     */
    public function getImageId()
    {
        return $this->imageId;
    }

    /**
     * @param string $imageId
     */
    public function setImageId($imageId)
    {
        $this->imageId = $imageId;
    }

    /**
     * @param string $classes
     *
     * @return $this
     */
    public function addClasses($classes)
    {
        if ( ! is_array($classes)) {
            $classes = [$classes];
        }
        foreach ($classes as $class) {
            $this->classes[] = $class;
        }

        return $this;
    }

    /**
     * @param boolean $lightBox
     */
    public function setLightBox($lightBox)
    {
        $this->lightBox = $lightBox;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param array $classes
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;
    }
}
