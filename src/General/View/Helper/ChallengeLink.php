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

use General\Entity\Challenge;

/**
 * Create a link to an challenge
 *
 * @category    General
 * @package     View
 * @subpackage  Helper
 */
class ChallengeLink extends AbstractHelper
{

    /**
     * @param Challenge $challenge
     * @param string    $action
     * @param string    $show
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        Challenge $challenge = null,
        $action = 'view',
        $show = 'name'
    )
    {
        $translate = $this->view->plugin('translate');
        $url       = $this->view->plugin('url');
        $serverUrl = $this->view->plugin('serverUrl');
        $isAllowed = $this->view->plugin('isAllowed');

        switch ($action) {
            case 'new':
                $router    = 'zfcadmin/challenge-manager/new';
                $text      = sprintf($translate("txt-new-challenge"));
                $challenge = new General();
                break;
            case 'edit':
                $router = 'zfcadmin/challenge-manager/edit';
                $text   = sprintf($translate("txt-edit-challenge-%s"), $challenge);
                break;
            case 'view':
                $router = 'route-' . $challenge->get("underscore_full_entity_name");
                $text   = sprintf($translate("txt-view-challenge-%s"), $challenge);
                break;
            default:
                throw new \Exception(sprintf("%s is an incorrect action for %s", $action, __CLASS__));
        }


        $params = array(
            'id'     => $challenge->getId(),
            'docRef' => $challenge->getDocRef(),
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
                $linkContent[] = $challenge;
                break;
            default:
                $linkContent[] = $challenge;
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
