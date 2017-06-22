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
use General\Entity\Country;
use Zend\Navigation\Page\Mvc;

/**
 * Class CountryLabel
 *
 * @package General\Navigation\Invokable
 */
class CountryLabel extends AbstractNavigationInvokable
{
    /**
     * Set the Country navigation label
     *
     * @param Mvc $page
     *
     * @return void
     */
    public function __invoke(Mvc $page)
    {
        if ($this->getEntities()->containsKey(Country::class)) {
            /** @var Country $country */
            $country = $this->getEntities()->get(Country::class);

            $page->setParams(
                array_merge(
                    $page->getParams(),
                    ['id' => $country->getId()]
                )
            );
            $label = (string)$country;
        } else {
            $label = $this->translate('txt-nav-view');
        }
        $page->set('label', $label);
    }
}
