<?php

/**
 * ITEA Office copyright message placeholder.
 *
 * PHP Version 5
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   2004-2016 ITEA Office
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

namespace General\View\Helper;

use General\Entity\Title;

/**
 * Create a link to an title.
 *
 * @category   General
 */
class TitleLink extends LinkAbstract
{
    /**
     * @var Title
     */
    protected $title;

    /**
     * @param Title  $title
     * @param string $action
     * @param string $show
     *
     * @return string
     *
     * @throws \Exception
     */
    public function __invoke(
        Title $title = null,
        $action = 'view',
        $show = 'name'
    ) {
        $this->setTitle($title);
        $this->setAction($action);
        $this->setShow($show);

        if (!is_null($title)) {
            $this->addRouterParam('id', $title->getId());
            $this->setShowOptions([
                'name' => $title->getName(),
            ]);
        }

        return $this->createLink();
    }

    /**
     * Parse the action.
     *
     * @throws \Exception
     */
    public function parseAction()
    {
        switch ($this->getAction()) {
            case 'list':
                $this->setRouter('zfcadmin/title/list');
                $this->setText($this->translate("txt-title-list"));
                break;
            case 'new':
                $this->setRouter('zfcadmin/title/new');
                $this->setText($this->translate("txt-new-title"));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/title/edit');
                $this->setText(sprintf($this->translate("txt-edit-title-%s"), $this->getTitle()));
                break;
            case 'view':
                $this->setRouter('zfcadmin/title/view');
                $this->setText(sprintf($this->translate("txt-view-title-%s"), $this->getTitle()));
                break;
            default:
                throw new \Exception(sprintf("%s is an incorrect action for %s", $this->getAction(), __CLASS__));
        }
    }

    /**
     * @return Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param Title $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
