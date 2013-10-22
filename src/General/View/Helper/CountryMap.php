<?php

/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    General
 * @package     View
 * @subpackage  Helper
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
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

//        $objCountryCollection = new CustomCollection(
//            'SELECT country.country_cd ' .
//            'FROM affiliation INNER ' .
//            'JOIN organisation ON affiliation.organisation_id = organisation.organisation_id INNER ' .
//            'JOIN country ON organisation.country_id = country.country_id ' .
//            'WHERE (not country.country_id = 0) ' .
//            ' AND  affiliation.date_end is null ' .
//            ' AND  project_id in (SELECT project_id FROM project_version WHERE (approved=1) and (type_id = 2)) ' .
//            ' AND  project_id not in (SELECT project_id FROM project_version WHERE type_id = 4) ' .
//            'GROUP BY country.country_id, country.country ' .
//            'ORDER BY country', 'country');
//
//        $html .= " var countries = [\n";
//        foreach ($objCountryCollection->items() as $objCountry) {
//            $html .= '"' . $objCountry->country_cd . '", ';
//        }
//        $html .= "];\n";

        if ($clickable) {
            $html[] = "var clickable = 1;";
        } else {
            $html[] = "var clickable = 0;";
        }


        $html[] = <<< EOT

                $(function(){
                    $('#world-map-gdp').vectorMap({
                        map: 'europe_mill_en',
                        series: {
                            regions: [{
                                values: gdpData,
                                scale: ['$color', '$colorFaded'],
                                normalizeFunction: 'polynomial'
                            }]
                        },
                        onRegionClick: function(e, code) {
                            if (clickable == 1 && jQuery.inArray(code, countries) != '-1')
                            {
                                window.location='?code=' + code;
                            }
                        },
                        onRegionLabelShow: function(e, el, code){
                        }
                    });
                 });

              
EOT;
        $this->getView()->headScript()->appendFile(
            'assets/js/jvectormap.js',
            'text/javascript'
        );
        $this->getView()->headScript()->appendScript(implode('', $html));

        return '<h3>Map</h3><div id="world-map-gdp" style="height: 340px"></div>';
    }
}
