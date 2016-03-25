<?php

/**
 * Jield webdev copyright message placeholder.
 *
 * @category    General
 *
 * @author      Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2004-2015 Jield (http://jield.nl)
 */

namespace General\View\Helper;

use General\Entity\WebInfo;

/**
 * Create a link to an equipment.
 *
 * @category    WebInfo
 */
class WebInfoLink extends LinkAbstract
{
    /**
     * @param WebInfo $webInfo
     * @param         $action
     * @param         $show
     *
     * @return null|string
     *
     * @throws \Exception
     */
    public function __invoke(WebInfo $webInfo = null, $action = 'view', $show = 'name')
    {
        $this->setWebInfo($webInfo);
        $this->setAction($action);
        $this->setShow($show);

        if (!$this->hasAccess($this->getWebInfo(), 'General\Acl\Assertion\WebInfo', $this->getAction())) {
            return $action === 'view' ? (string)$this->getWebInfo() : null;
        }

        $this->setShowOptions([
                'name'            => $this->getWebInfo(),
                'alternativeShow' => $this->getAlternativeShow(),
            ]);
        $this->addRouterParam('id', $this->getWebInfo()->getId());

        return $this->createLink();
    }

    /**
     * @throws \Exception
     */
    public function parseAction()
    {
        switch ($this->getAction()) {
            case 'list':
                $this->setRouter('zfcadmin/web-info/list');
                $this->setText(sprintf($this->translate('txt-web-info-list')));
                break;
            case 'new':
                $this->setRouter('zfcadmin/web-info/new');
                $this->setText(sprintf($this->translate('txt-create-new-web-info')));
                break;
            case 'view':
                $this->setRouter('zfcadmin/web-info/view');
                $this->setText(sprintf($this->translate('txt-view-web-info-%s'), $this->getWebInfo()));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/web-info/edit');
                $this->setText(sprintf($this->translate('txt-edit-web-info-%s'), $this->getWebInfo()));
                break;
            default:
                throw new \Exception(sprintf('%s is an incorrect action for %s', $this->getAction(), __CLASS__));
        }
    }
}
