<?php

/**
 * ITEA Office all rights reserved
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\View\Helper\Country;

use General\Entity\Country;
use General\Service\GeneralService;
use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager;
use function array_key_exists;
use function is_array;
use function json_encode;

/**
 * Create a country map based on a list of countries.
 *
 * @category   General
 */
final class CountryMap extends AbstractHelper
{
    private GeneralService $generalService;
    private HelperPluginManager $viewHelperManager;

    public function __construct(GeneralService $generalService, HelperPluginManager $viewHelperManager)
    {
        $this->generalService = $generalService;
        $this->viewHelperManager = $viewHelperManager;
    }

    public function __invoke(
        array $countries,
        Country $selectedCountry = null,
        array $options = [],
        bool $world = false
    ) {
        $clickable = array_key_exists('clickable', $options) ? $options['clickable'] : true;
        $pointer = $clickable ? 'pointer' : 'default';
        $clickable = $clickable ? 'true' : 'false';
        $colorMin = $options['colorMin'] ?? '#005C00';
        $colorMax = $options['colorMax'] ?? '#00a651';
        $regionFill = $options['regionFill'] ?? '#C5C7CA';
        $height = $options['height'] ?? '400px';
        $tipData = $options['tipData'] ?? null;
        $focusOn = $options['focusOn'] ?? ['x' => 0.5, 'y' => 0.5, 'scale' => 1];
        $focusOn = is_array($focusOn) ? json_encode($focusOn, JSON_THROW_ON_ERROR, 512) : "'" . $focusOn . "'";
        $zoomOnScroll = array_key_exists('zoomOnScroll', $options) ? $options['zoomOnScroll'] : false;
        $zoomOnScroll = $zoomOnScroll ? 'true' : 'false';

        $js = $countryList = [];
        $js[] = 'var data = {';
        foreach ($countries as $country) {
            $countryList[] = '"' . $country->getCd() . '": ';
            $countryList[] = ($selectedCountry !== null && ($country->getId() === $selectedCountry->getId())) ? 2
                : 1;
            $countryList[] = ',';
        }
        $js[] = substr(implode('', $countryList), 0, -1);
        $js[] = "},\n";
        if (is_array($tipData)) {
            $js[] = '            tipData = ' . json_encode($tipData, JSON_THROW_ON_ERROR, 512) . ",\n";
        }
        $js[] = '            clickable = ' . $clickable . ",\n";
        $js[] = '            countries = [';
        $countryList = [];
        foreach ($this->generalService->findAll(Country::class) as $country) {
            $countryList[] = '"' . $country->getCd() . '",';
        }
        $js[] = substr(implode('', $countryList), 0, -1);
        $js[] = '];';
        $data = implode('', $js);


        $map = $world ? 'world_mill' : 'europe_mill_en';

        $jQuery
            = <<< EOT
$(function () {
        $data
        $('#country-map').vectorMap({
            map: '$map',
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
                if (clickable && (countries.indexOf(code) !== -1)) {
                    window.location.href = '/country/code/'+code;
                }
            },
            onRegionTipShow: function (e, el, code) {
                if (typeof tipData != 'undefined' && tipData[code]) {
                    var html = '<div class="tip-title">'+tipData[code]['title']+'</div>', list = tipData[code]['data'];
                    for (var i in list) {
                        for (var key in list[i]) {
                            html += '<div><span class="tip-key">'+key+': </span><span class="tip-value">'+list[i][key]+'</span></div>';
                        };
                    }
                    el.html(html);
                } else {
                    return false;
                }
            }
        });
    });
EOT;
        $this->viewHelperManager->get('headlink')->prependStylesheet(
            'assets/' . ITEAOFFICE_HOST
            . '/css/jvectormap.css',
            'screen'
        );
        $this->viewHelperManager->get('headscript')->appendFile(
            'assets/' . ITEAOFFICE_HOST . '/js/jvectormap.js',
            'text/javascript'
        );
        $this->viewHelperManager->get('headscript')->appendScript($jQuery);

        return '<div id="country-map" style="height: ' . $height . ';"></div>';
    }
}
