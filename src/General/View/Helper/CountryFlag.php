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
 * Create a link to an project
 *
 * @category    General
 * @package     View
 * @subpackage  Helper
 */
class CountryFlag extends AbstractHelper
{

    /**
     * @param Country $country
     * @param int     $width
     *
     * @return string
     */
    public function __invoke(Country $country, $width = 20)
    {
        $url  = $this->getView()->plugin('url');
        $flag = $country->getFlag();

        if (is_null($flag)) {
            return '';
        }

        /**
         * Check if the file is cached and if so, pull it from the assets-folder
         */
        $router = 'assets/country-flag';
        if (!file_exists($flag->getCacheFileName())) {
            file_put_contents(
                $flag->getCacheFileName(),
                is_resource($flag->getObject()) ? stream_get_contents($flag->getObject()) : $flag->getObject()
            );
        }

        $imageUrl = '<img src="%s" id="%s" width="%s">';

        $params = array(
            'ext'  => 'png',
            'iso3' => strtolower($country->getIso3()),
        );

        $image = sprintf(
            $imageUrl,
            $url($router, $params),
            'country_flag_' . $country->getIso3(),
            $width
        );

        return $image;
    }
}
