<?php

namespace General\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $styleLocations = [];
    /**
     * @var string
     */
    protected $imageLocation = 'img';
    /**
     * @var string
     */
    protected $imageNotFound = 'image_not_found.jpg';
    /**
     * String of the GeoIP service.
     *
     * @var string
     */
    protected $geoIpServiceURL = 'http://freegeoip.net/json/%s';

    /**
     * Color to use on country map.
     *
     * @var string
     */
    protected $countryColor = '#00a651';

    /**
     * Color to use on country map for faded countries.
     *
     * @var string
     */
    protected $countryColorFaded = '#005C00';

    /**
     * @return array
     */
    public function getStyleLocations()
    {
        return $this->styleLocations;
    }

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
     * @return string
     */
    public function getImageLocation()
    {
        return $this->imageLocation;
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
    public function getImageNotFound()
    {
        return $this->imageNotFound;
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
    public function getCountryColor()
    {
        return $this->countryColor;
    }

    /**
     * @param $countryColor
     *
     * @return ModuleOptions
     */
    public function setCountryColor($countryColor)
    {
        $this->countryColor = $countryColor;

        return $this;
    }

    /**
     * Returns the assigned hex color of the country map.
     *
     * @return string
     */
    public function getCountryColorFaded()
    {
        return $this->countryColorFaded;
    }

    /**
     * Returns the assigned hex color of the country map.
     *
     * @param string $countryColorFaded
     *
     * @return ModuleOptions
     */
    public function setCountryColorFaded($countryColorFaded)
    {
        $this->countryColorFaded = $countryColorFaded;

        return $this;
    }

    /**
     * @return string
     */
    public function getGeoIpServiceURL()
    {
        return $this->geoIpServiceURL;
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
}
