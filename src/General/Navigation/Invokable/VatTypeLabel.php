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
use General\Entity\VatType;
use Zend\Navigation\Page\Mvc;

/**
 * Class VatTypeLabel
 *
 * @package General\Navigation\Invokable
 */
class VatTypeLabel extends AbstractNavigationInvokable
{
    /**
     * Set the VatType navigation label
     *
     * @param Mvc $page
     *
     * @return void
     */
    public function __invoke(Mvc $page)
    {
        if ($this->getEntities()->containsKey(VatType::class)) {
            /** @var VatType $type */
            $type = $this->getEntities()->get(VatType::class);

            $page->setParams(array_merge(
                $page->getParams(), ['id' => $type->getId()]
            ));
            $label = (string) $type;
        } else {
            $label = $this->translate('txt-nav-view');
        }
        $page->set('label', $label);
    }
}
