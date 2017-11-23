<?php

/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\View\Helper;

use General\Entity\EmailMessage;

/**
 * Create a link to an emailMessage.
 *
 * @category   General
 */
class EmailMessageLink extends LinkAbstract
{
    /**
     * @var EmailMessage
     */
    protected $emailMessage;

    /**
     * @param EmailMessage $emailMessage
     * @param string $action
     * @param string $show
     *
     * @return string
     *
     * @throws \Exception
     */
    public function __invoke(
        EmailMessage $emailMessage = null,
        $action = 'view',
        $show = 'name'
    ): string {
        $this->setEmailMessage($emailMessage);
        $this->setAction($action);
        $this->setShow($show);

        $this->addRouterParam('id', $this->getEmailMessage()->getId());
        $this->setShowOptions([
            'subject'      => $this->getEmailMessage()->getSubject(),
            'emailAddress' => $this->getEmailMessage()->getEmailAddress(),
        ]);


        return $this->createLink();
    }

    /**
     * @return EmailMessage
     */
    public function getEmailMessage(): EmailMessage
    {
        if (\is_null($this->emailMessage)) {
            $this->emailMessage = new EmailMessage();
        }

        return $this->emailMessage;
    }

    /**
     * @param EmailMessage $emailMessage
     */
    public function setEmailMessage($emailMessage)
    {
        $this->emailMessage = $emailMessage;
    }

    /**
     * Parse the action.
     *
     * @throws \Exception
     */
    public function parseAction(): void
    {
        switch ($this->getAction()) {
            case 'list':
                $this->setRouter('zfcadmin/email/list');
                $this->setText($this->translate("txt-email-message-list"));
                break;
            case 'view':
                $this->setRouter('zfcadmin/email/view');
                $this->setText(sprintf($this->translate("txt-view-email-message-%s"), $this->getEmailMessage()));
                break;
            default:
                throw new \Exception(sprintf("%s is an incorrect action for %s", $this->getAction(), __CLASS__));
        }
    }
}
