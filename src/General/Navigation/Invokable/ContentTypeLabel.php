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
use General\Entity\ContentType;
use Zend\Navigation\Page\Mvc;

/**
 * Class ContentTypeLabel
 *
 * @package General\Navigation\Invokable
 */
class ContentTypeLabel extends AbstractNavigationInvokable
{
    /**
     * Set the ContentType navigation label
     *
     * @param Mvc $page
     *
     * @return void
     */
    public function __invoke(Mvc $page)
    {
        if ($this->getEntities()->containsKey(ContentType::class)) {
            /** @var ContentType $type */
            $type = $this->getEntities()->get(ContentType::class);

            $page->setParams(array_merge(
                $page->getParams(),
                ['id' => $type->getId()]
            ));
            $label = $type->getDescription();
        } else {
            $label = $this->translate('txt-nav-view');
        }
        $page->set('label', $label);
    }
}
