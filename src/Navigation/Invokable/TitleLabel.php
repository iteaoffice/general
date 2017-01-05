<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    General
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/invoice for the canonical source repository
 */

namespace General\Navigation\Invokable;

use Admin\Navigation\Invokable\AbstractNavigationInvokable;
use General\Entity\Title;
use Zend\Navigation\Page\Mvc;

/**
 * Class TitleLabel
 *
 * @package General\Navigation\Invokable
 */
class TitleLabel extends AbstractNavigationInvokable
{
    /**
     * Set the Title navigation label
     *
     * @param Mvc $page
     *
     * @return void
     */
    public function __invoke(Mvc $page)
    {
        if ($this->getEntities()->containsKey(Title::class)) {
            /** @var Title $title */
            $title = $this->getEntities()->get(Title::class);

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    ['id' => $title->getId()]
                )
            );
            $label = $title->getName();
        } else {
            $label = $this->translate('txt-nav-view');
        }
        $page->set('label', $label);
    }
}
