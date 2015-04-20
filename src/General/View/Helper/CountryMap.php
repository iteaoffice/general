<?php

/**
 * ITEA Office copyright message placeholder.
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2015 ITEA Office (http://itea3.org)
 */

namespace General\View\Helper;

use General\Entity\Country;
use General\Service\GeneralService;
use General\Service\GeneralServiceAwareInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Create a country map based on a list of countries.
 *
 * @category   General
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
     * @param array     $options
     *
     * @return string
     */
    
    public function __invoke(array $countries, Country $selectedCountry = null, array $options = [])
    {
        $clickable = array_key_exists('clickable', $options) ? $options['clickable'] : true;
        $pointer = $clickable ? 'pointer' : 'default';
        $clickable = $clickable ? 'true' : 'false';
        $colorMin = isset($options['colorMin']) ? $options['colorMin'] : '#00a651';
        $colorMax = isset($options['colorMax']) ? $options['colorMax'] : '#005C00';
        $regionFill = isset($options['regionFill']) ? $options['regionFill'] : '#C5C7CA';
        $height = isset($options['height']) ? $options['height'] : '400px';
        $tipData = isset($options['tipData']) ? $options['tipData'] : null;
        $focusOn = isset($options['focusOn']) ? $options['focusOn'] : ['x' => 0.5, 'y' => 0.5, 'scale' => 1];
        $focusOn = is_array($focusOn) ? json_encode($focusOn) : "'".$focusOn."'";
        $zoomOnScroll = array_key_exists('zoomOnScroll', $options) ? $options['zoomOnScroll'] : false;
        $zoomOnScroll = $zoomOnScroll ? 'true' : 'false';
        
        $js = $countryList = [];
        $js[] = "var data = {";
        foreach ($countries as $country) {
            $countryList[] = '"'.$country->getCd().'": ';
            $countryList[] = (!is_null($selectedCountry) && ($country->getId() === $selectedCountry->getId())) ? 2 : 1;
            $countryList[] = ",";
        }
        $js[] = substr(implode('', $countryList), 0, -1);
        $js[] = "},\n";
        if(is_array($tipData)){
            $js[] = "            tipData = ".json_encode($tipData).",\n";
        }
        $js[] = "            clickable = ".$clickable.",\n";
        $js[] = "            countries = [";
        $countryList = [];
        foreach ($this->getGeneralService()->findAll('country') as $country) {
            $countryList[] = '"'.$country->getCd().'",';
        }
        $js[] = substr(implode('', $countryList), 0, -1);
        $js[] = "];";
        $data = implode('', $js);
        
        $jQuery = <<< EOT
$(function() {
        $data
        $('#country-map').vectorMap({
            map: 'europe_mill_en',
            backgroundColor: 'transparent',
            series: {
                regions: [{
                    values: data,
                    scale: ['$colorMin', '$colorMax'],
                    normalizeFunction: 'polynomial',
                }]
            },
            focusOn: $focusOn,
            zoomOnScroll: $zoomOnScroll,
            regionStyle: {
                initial: {
                    fill: '$regionFill'
                },
                hover: {
                    cursor: '$pointer'
                }
            },
            onRegionClick: function (e, code) {
                if ((clickable === 1) && jQuery.inArray(code, countries) !== -1) {
                    window.location='/country/code/'+code;
                }
            },
            onRegionTipShow: function (e, el, code) {
                if (tipData[code]) {
                    var html = '<div class="tip-title">'+tipData[code]['title']+'</div>', list = tipData[code]['data'];
                    for (var i in list) {
                        for (var key in list[i]) {
                            html += '<div><span class="tip-key">'+key+': </span><span class="tip-value">'+list[i][key]+'</span></div>';
                        };
                    }
                    el.html(html);
                }else{
                    return false;
                }
            }
        });
    });
EOT;
        $this->serviceLocator->get('headlink')->prependStylesheet(
            'assets/'.DEBRANOVA_HOST.'/css/jvectormap.css', 'screen'
        );
        $this->serviceLocator->get('headscript')->appendFile(
            'assets/'.DEBRANOVA_HOST.'/js/jvectormap.js', 'text/javascript'
        );
        $this->serviceLocator->get('headscript')->appendScript($jQuery);

        return '<div id="country-map" style="height: '.$height.';"></div>';
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
