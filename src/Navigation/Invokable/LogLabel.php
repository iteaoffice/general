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

declare(strict_types=1);

namespace General\Navigation\Invokable;

use Admin\Navigation\Invokable\AbstractNavigationInvokable;
use General\Entity\Log;
use Zend\Navigation\Page\Mvc;

/**
 * Class EmailMessageLabel
 *
 * @package General\Navigation\Invokable
 */
class LogLabel extends AbstractNavigationInvokable
{
    /**
     * Set the EmailMessage navigation label
     *
     * @param Mvc $page
     *
     * @return void
     */
    public function __invoke(Mvc $page): void
    {
        $label = $this->translate('txt-nav-view');

        if ($this->getEntities()->containsKey(Log::class)) {
            /** @var Log $log */
            $log = $this->getEntities()->get(Log::class);

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    ['id' => $log->getId()]
                )
            );
            $label = (string) \substr($log->getEvent(), 0, 25);
        }
        $page->set('label', $label);
    }
}
