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
use General\Entity\Challenge;
use Zend\Navigation\Page\Mvc;

/**
 * Class ChallengeLabel
 *
 * @package General\Navigation\Invokable
 */
class ChallengeLabel extends AbstractNavigationInvokable
{
    /**
     * Set the Challenge navigation label
     *
     * @param Mvc $page
     *
     * @return void
     */
    public function __invoke(Mvc $page)
    {
        if ($this->getEntities()->containsKey(Challenge::class)) {
            /** @var Challenge $challenge */
            $challenge = $this->getEntities()->get(Challenge::class);

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    ['id' => $challenge->getId()]
                )
            );
            $label = (string)$challenge;
        } else {
            $label = $this->translate('txt-nav-view');
        }
        $page->set('label', $label);
    }
}
