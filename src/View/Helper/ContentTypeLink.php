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

use General\Entity\ContentType;

/**
 * Create a link to an equipment.
 *
 * @category    ContentType
 */
class ContentTypeLink extends LinkAbstract
{
    /**
     * @param ContentType $contentType
     * @param             $action
     * @param             $show
     *
     * @return null|string
     *
     * @throws \Exception
     */
    public function __invoke(
        ContentType $contentType = null,
        $action = 'view',
        $show = 'name'
    ) {
        $this->setContentType($contentType);
        $this->setAction($action);
        $this->setShow($show);

        if (!$this->hasAccess($this->getContentType(), \General\Acl\Assertion\ContentType::class, $this->getAction())) {
            return $action === 'view' ? (string)$this->getContentType() : null;
        }

        $this->setShowOptions([
            'name'            => $this->getContentType(),
            'extension'       => $this->getContentType()->getExtension(),
            'description'     => $this->getContentType()->getDescription(),
            'content-type'    => $this->getContentType()->getContentType(),
            'alternativeShow' => $this->getAlternativeShow(),
        ]);
        $this->addRouterParam('id', $this->getContentType()->getId());

        return $this->createLink();
    }

    /**
     * @throws \Exception
     */
    public function parseAction()
    {
        switch ($this->getAction()) {
            case 'list':
                $this->setRouter('zfcadmin/content-type/list');
                $this->setText(sprintf($this->translate('txt-content-type-list')));
                break;
            case 'new':
                $this->setRouter('zfcadmin/content-type/new');
                $this->setText(sprintf($this->translate('txt-create-new-content-type')));
                break;
            case 'view':
                $this->setRouter('zfcadmin/content-type/view');
                $this->setText(sprintf(
                    $this->translate('txt-view-content-type-%s'),
                    $this->getContentType()->getDescription()
                ));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/content-type/edit');
                $this->setText(sprintf(
                    $this->translate('txt-edit-content-type-%s'),
                    $this->getContentType()->getDescription()
                ));
                break;
            default:
                throw new \Exception(sprintf('%s is an incorrect action for %s', $this->getAction(), __CLASS__));
        }
    }
}
