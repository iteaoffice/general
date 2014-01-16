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
     * String of the GeoIP service
     *
     * @var string
     */
    protected $geoIpServiceURL = 'http://freegeoip.net/json/%s';

    /**
     * @param array $styleLocations
     *
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
     *                              return ModuleOptions
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
     *
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

    /**
     * @param string $geoIpServiceURL
     *
     * @return ModuleOptions
     */
    public function setGeoIpServiceURL($geoIpServiceURL)
    {
        $this->geoIpServiceURL = $geoIpServiceURL;

        return $this;
    }

    /**
     * @return string
     */
    public function getGeoIpServiceURL()
    {
        return $this->geoIpServiceURL;
    }
}
