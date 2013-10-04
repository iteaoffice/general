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
 * Create a link to an organisation
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
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        Country $country = null,
        $action = 'view',
        $show = 'name'
    )
    {
        $translate = $this->view->plugin('translate');
        $url       = $this->view->plugin('url');
        $serverUrl = $this->view->plugin('serverUrl');
        $isAllowed = $this->view->plugin('isAllowed');

//        if (!$isAllowed('organisation', $action)) {
//            if ($action === 'view' && $show === 'name') {
//                return $organisationService;
//            }
//
//            return '';
//        }

        switch ($action) {
            case 'new':
                $router  = 'zfcadmin/organisation-manager/new';
                $text    = sprintf($translate("txt-new-organisation"));
                $country = new General();
                break;
            case 'edit':
                $router = 'zfcadmin/organisation-manager/edit';
                $text   = sprintf($translate("txt-edit-organisation-%s"), $country);
                break;
            case 'view':
                $router = 'route-10';
                $text   = sprintf($translate("txt-view-organisation-%s"), $country);
                break;
            default:
                throw new \Exception(sprintf("%s is an incorrect action for %s", $action, __CLASS__));
        }



        $params = array(
            'id'     => $country->getId(),
            'docRef' => $country->getIso3(),
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
