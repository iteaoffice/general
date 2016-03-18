<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * PHP Version 5
 *
 * @category    General
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   2004-2016 ITEA Office
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/invoice for the canonical source repository
 */

namespace General\Navigation\Invokable;

use Admin\Navigation\Invokable\AbstractNavigationInvokable;
use General\Entity\WebInfo;
use Zend\Navigation\Page\Mvc;

/**
 * Class WebInfoLabel
 *
 * @package General\Navigation\Invokable
 */
class WebInfoLabel extends AbstractNavigationInvokable
{
    /**
     * Set the WebInfo navigation label
     *
     * @param Mvc $page
     *
     * @return void
     */
    public function __invoke(Mvc $page)
    {
        if ($this->getEntities()->containsKey(WebInfo::class)) {
            /** @var WebInfo $webInfo */
            $webInfo = $this->getEntities()->get(WebInfo::class);

            $page->setParams(array_merge(
                $page->getParams(),
                ['id' => $webInfo->getId()]
            ));
            $label = (string) $webInfo;
        } else {
            $label = $this->translate('txt-nav-view');
        }
        $page->set('label', $label);
    }
}
