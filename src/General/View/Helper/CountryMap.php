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

use Zend\View\Helper\AbstractHelper;

use General\Entity\Country;

/**
 * Create a country map based on a list of countries
 *
 * @category    General
 * @package     View
 * @subpackage  Helper
 */
class CountryMap extends AbstractHelper
{

    /**
     * @param Country[] $countries
     * @param Country   $selectedCountry
     * @param bool      $clickable
     *
     * @return string
     */
    public function __invoke(array $countries, Country $selectedCountry = null, $clickable = true)
    {

        $color      = '#005C00';
        $colorFaded = '#009900';
        $html       = array();
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

        $html[] = <<< EOT

                $(function(){
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
                        onRegionClick: function(e, code) {
                            if (clickable == 1 && jQuery.inArray(code, countries) != '-1') {
                                window.location='?code=' + code;
                            }
                        },
                        onRegionLabelShow: function(e, el, code){
                        }
                    });
                 });

EOT;
        $this->getView()->headScript()->appendFile(
            'assets/itea/js/jvectormap.js',
            'text/javascript'
        );
        $this->getView()->headScript()->appendScript(implode('', $html));

        return '<h3>Map</h3><div id="world-map-gdp" style="height: 340px"></div>';
    }
}
