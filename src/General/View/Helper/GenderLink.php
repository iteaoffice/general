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

use General\Entity\Gender;

/**
 * Create a link to an gender.
 *
 * @category   General
 */
class GenderLink extends LinkAbstract
{
    /**
     * @var Gender
     */
    protected $gender;

    /**
     * @param Gender $gender
     * @param string $action
     * @param string $show
     *
     * @return string
     *
     * @throws \Exception
     */
    public function __invoke(
        Gender $gender = null,
        $action = 'view',
        $show = 'name'
    ) {
        $this->setGender($gender);
        $this->setAction($action);
        $this->setShow($show);

        if (!is_null($gender)) {
            $this->addRouterParam('id', $gender->getId());
            $this->setShowOptions([
                'name' => $gender->getName(),
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
                $this->setRouter('zfcadmin/gender/list');
                $this->setText($this->translate("txt-gender-list"));
                break;
            case 'new':
                $this->setRouter('zfcadmin/gender/new');
                $this->setText($this->translate("txt-new-gender"));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/gender/edit');
                $this->setText(sprintf($this->translate("txt-edit-gender-%s"), $this->getGender()));
                break;
            case 'view':
                $this->setRouter('zfcadmin/gender/view');
                $this->setText(sprintf($this->translate("txt-view-gender-%s"), $this->getGender()));
                break;
            default:
                throw new \Exception(sprintf("%s is an incorrect action for %s", $this->getAction(), __CLASS__));
        }
    }

    /**
     * @return Gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param Gender $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }
}
