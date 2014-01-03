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
 * Create a link to an country
 *
 * @category    General
 * @package     View
 * @subpackage  Helper
 */
class CountryLink extends AbstractHelper
{

    /**
     * @param Country $country
     * @param string  $action
     * @param string  $show
     * @param string  $customShow
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        Country $country = null,
        $action = 'view',
        $show = 'name',
        $customShow = null
    )
    {
        $translate   = $this->view->plugin('translate');
        $url         = $this->view->plugin('url');
        $serverUrl   = $this->view->plugin('serverUrl');
        $isAllowed   = $this->view->plugin('isAllowed');
        $countryFlag = $this->view->plugin('countryFlag');

//        if (!$isAllowed('country', $action)) {
//            if ($action === 'view' && $show === 'name') {
//                return $countryService;
//            }
//
//            return '';
//        }

        switch ($action) {
            case 'new':
                $router  = 'zfcadmin/country-manager/new';
                $text    = sprintf($translate("txt-new-country"));
                $country = new Country();
                break;
            case 'edit':
                $router = 'zfcadmin/country-manager/edit';
                $text   = sprintf($translate("txt-edit-country-%s"), $country);
                break;
            case 'view':
                $router = 'route-' . $country->get('underscore_full_entity_name');
                $text   = sprintf($translate("txt-view-country-%s"), $country);
                break;
            case 'view-project':
                $router = 'route-' . $country->get('underscore_full_entity_name') . '-project';
                $text   = sprintf($translate("txt-view-project-for-country-%s"), $country);
                break;
            case 'view-organisation':
                $router = 'route-' . $country->get('underscore_full_entity_name') . '-organisation';
                $text   = sprintf($translate("txt-view-organisation-for-country-%s"), $country);
                break;
            case 'view-article':
                $router = 'route-' . $country->get('underscore_full_entity_name') . '-article';
                $text   = sprintf($translate("txt-view-article-for-country-%s"), $country);
                break;
            default:
                throw new \InvalidArgumentException(sprintf("%s is an incorrect action for %s", $action, __CLASS__));
        }

        $params = array(
            'id'     => $country->getId(),
            'docRef' => strtolower($country->getDocRef()),
            'entity' => 'country'
        );

        $classes     = array();
        $linkContent = array();

        switch ($show) {
            case 'icon':
                if ($action === 'edit') {
                    $linkContent[] = '<i class="icon-pencil"></i>';
                } elseif ($action === 'delete') {
                    $linkContent[] = '<i class="icon-remove"></i>';
                } else {
                    $linkContent[] = '<i class="icon-info-sign"></i>';
                }
                break;
            case 'button':
                $linkContent[] = '<i class="icon-pencil icon-white"></i> ' . $text;
                $classes[]     = "btn btn-primary";
                break;
            case 'name':
                $linkContent[] = $country;
                break;
            case 'more':
                $linkContent[] = $translate("txt-read-more");
                break;
            case 'custom':
                if (is_null($customShow)) {
                    throw new \InvalidArgumentException(sprintf("CustomShow cannot be empty"));
                }
                $linkContent[] = $customShow;
                break;
            case 'flag':
                $linkContent[] = $countryFlag($country, 40);
                break;
            default:
                $linkContent[] = $country;
                break;
        }

        $uri = '<a href="%s" title="%s" class="%s">%s</a>';

        return sprintf(
            $uri,
            $serverUrl->__invoke() . $url($router, $params),
            $text,
            implode($classes),
            implode($linkContent)
        );
    }
}
