<?php

/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     View
 * @subpackage  Helper
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\View\Helper;

use General\Entity\Country;
use General\Service\GeneralService;
use General\Service\GeneralServiceAwareInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Create a country map based on a list of countries
 *
 * @category    General
 * @package     View
 * @subpackage  Helper
 */
class CountryMap extends HelperAbstract implements GeneralServiceAwareInterface
{
    /**
     * @var GeneralService
     */
    protected $generalService;

    /**
     * @param Country[] $countries
     * @param Country   $selectedCountry
     * @param bool      $clickable
     *
     * @return string
     */
    public function __invoke(array $countries, Country $selectedCountry = null, $clickable = true, $color = '#005C00', $colorFaded = '#009900', $showTitle = false)
    {
        $allCountries = $this->getGeneralService()->findAll('country');
        $html       = [];
        $html[]     = " var gdpData = {\n";
        foreach ($countries as $country) {
            $html[] = '"' . $country->getCd() . '": ';
            $html[] = (!is_null($selectedCountry) && $country->getId() == $selectedCountry->getId()) ? 2 : 1;
            $html[] = ",\n";
        }
        $html[] = "};\n";
        if ($clickable) {
            $html[] = "var clickable = 1;";
        } else {
            $html[] = "var clickable = 0;";
        }
        $html[] = " var countries = [\n";
        foreach ($allCountries as $country) {
            $html[] = '"' . $country->getCd() . '", ';
        }
        $html[] = "];\n";
        if ($clickable) {
            $html[] = "var clickable = 1;";
        } else {
            $html[] = "var clickable = 0;";
        }
        $html[] = <<< EOT

                $(function () {
                    $('#world-map-gdp').vectorMap({
                        map: 'europe_mill_en',
                        backgroundColor: 'transparent',
                        series: {
                            regions: [{
                                values: gdpData,
                                scale: ['$color', '$colorFaded'],
                                normalizeFunction: 'polynomial',
                            }]
                        },
                         regionStyle: {
                          initial: {
                            fill: '#C5C7CA'
                          }
                        },
                        onRegionClick: function (e, code) {
                            if (clickable == 1 && jQuery.inArray(code, countries) != '-1') {
                                window.location='/country/code/' + code;
                            }
                        },
                        onRegionLabelShow: function (e, el, code) {
                        }
                    });
                 });

EOT;
        $this->serviceLocator->get('headscript')->appendFile(
            'assets/' . DEBRANOVA_HOST . '/js/jvectormap.js',
            'text/javascript'
        );
        $this->serviceLocator->get('headscript')->appendScript(implode('', $html));

        $map = '';
        if ($showTitle) {
            $map .= '<h3>Map</h3>';
        }
        $map .=  '<div id="world-map-gdp" style="height: 340px"></div>';

        return $map;
    }

    /**
     * @return GeneralService
     */
    public function getGeneralService()
    {
        return $this->generalService;
    }

    /**
     * Set the service locator.
     *
     * @param GeneralService $generalService
     *
     * @return AbstractHelper
     */
    public function setGeneralService(GeneralService $generalService)
    {
        $this->generalService = $generalService;

        return $this;
    }
}
