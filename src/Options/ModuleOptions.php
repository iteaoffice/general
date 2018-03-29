<?php

declare(strict_types=1);

namespace General\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
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
     * @return string
     */
    public function getGeoIpServiceURL(): string
    {
        return $this->geoIpServiceURL;
    }

    /**
     * @param string $geoIpServiceURL
     *
     * @return ModuleOptions
     */
    public function setGeoIpServiceURL(string $geoIpServiceURL): ModuleOptions
    {
        $this->geoIpServiceURL = $geoIpServiceURL;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryColor(): string
    {
        return $this->countryColor;
    }

    /**
     * @param string $countryColor
     *
     * @return ModuleOptions
     */
    public function setCountryColor(string $countryColor): ModuleOptions
    {
        $this->countryColor = $countryColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryColorFaded(): string
    {
        return $this->countryColorFaded;
    }

    /**
     * @param string $countryColorFaded
     *
     * @return ModuleOptions
     */
    public function setCountryColorFaded(string $countryColorFaded): ModuleOptions
    {
        $this->countryColorFaded = $countryColorFaded;
        return $this;
    }
}
