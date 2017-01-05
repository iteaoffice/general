<?php
/**
 * ITEA Office all rights reserved
 *
 * @category   Content
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license    https://itea3.org/license.txt proprietary
 *
 * @link       https://itea3.org
 */

namespace General\View\Helper;

use Zend\View\Helper\Url;

/**
 * Create a link to an document.
 *
 * @category   Content
 *
 * @author     Johan van der Heide < johan . van . der . heide@itea3 . org >
 * @copyright  Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license    https://itea3.org/license.txt proprietary
 *
 * @link       https://itea3.org
 */
class PaginationLink extends LinkAbstract
{
    /**
     * @param $page
     * @param $show
     *
     * @return string
     */
    public function __invoke($page, $show)
    {
        $router = $this->getRouteMatch()->getMatchedRouteName();

        $params = array_merge(
            $this->getRouteMatch()->getParams(),
            [
                'page' => $page,
            ]
        );

        /**
         * @var $url Url
         */
        $url = $this->getHelperPluginManager()->get('url');

        $uri = '<a href="%s" title="%s">%s</a>';

        return sprintf($uri, $url($router, $params), sprintf($this->translate("txt-go-to-page-%s"), $show), $show);
    }
}
