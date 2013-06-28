<?php

namespace General\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{

    /**
     * @var string
     */
    protected $styleLocations = array();
    /**
     * @var string
     */
    protected $imageLocation = 'img';

    /**
     * @var string
     */
    protected $imageNotFound = 'image_not_found.jpg';


    /**
     * @param array $styleLocations
     * @return ModuleOptions
     */
    public function setStyleLocations($styleLocations)
    {
        $this->styleLocations = $styleLocations;
        return $this;
    }

    /**
     * @return array
     */
    public function getStyleLocations()
    {
        return $this->styleLocations;
    }

    /**
     * @param string $imageLocation
     * return ModuleOptions
     */
    public function setImageLocation($imageLocation)
    {
        $this->imageLocation = $imageLocation;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageLocation()
    {
        return $this->imageLocation;
    }

    /**
     * @param $imageNotFound
     * @return ModuleOptions
     */
    public function setImageNotFound($imageNotFound)
    {
        $this->imageNotFound = $imageNotFound;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageNotFound()
    {
        return $this->imageNotFound;
    }
}
