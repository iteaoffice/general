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
use General\Entity\Vat;
use Zend\Navigation\Page\Mvc;

/**
 * Class VatLabel
 *
 * @package General\Navigation\Invokable
 */
class VatLabel extends AbstractNavigationInvokable
{
    /**
     * Set the VAT navigation label
     *
     * @param Mvc $page
     *
     * @return void
     */
    public function __invoke(Mvc $page)
    {
        if ($this->getEntities()->containsKey(Vat::class)) {
            /** @var Vat $vat */
            $vat = $this->getEntities()->get(Vat::class);

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    ['id' => $vat->getId()]
                )
            );
            $label = (string)$vat;
        } else {
            $label = $this->translate('txt-nav-view');
        }
        $page->set('label', $label);
    }
}
