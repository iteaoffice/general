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

use General\Entity\Log;

/**
 * Create a link to an log.
 *
 * @category   General
 */
class LogLink extends LinkAbstract
{
    /**
     * @var Log
     */
    protected $log;

    /**
     * @param Log    $log
     * @param string $action
     * @param string $show
     *
     * @return string
     *
     * @throws \Exception
     */
    public function __invoke(
        Log $log = null,
        $action = 'view',
        $show = 'name'
    ): string {
        $this->setLog($log);
        $this->setAction($action);
        $this->setShow($show);

        $this->addRouterParam('id', $this->getLog()->getId());
        $this->setShowOptions(
            [
                'event' => \substr($this->getLog()->getEvent(), 0, 50),
            ]
        );


        return $this->createLink();
    }

    /**
     * @return Log
     */
    public function getLog(): Log
    {
        if (\is_null($this->log)) {
            $this->log = new Log();
        }

        return $this->log;
    }

    /**
     * @param Log $log
     */
    public function setLog($log): void
    {
        $this->log = $log;
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
                $this->setRouter('zfcadmin/log/list');
                $this->setText($this->translate("txt-log-message-list"));
                break;
            case 'view':
                $this->setRouter('zfcadmin/log/view');
                $this->setText(sprintf($this->translate("txt-view-log-message-%s"), $this->getLog()));
                break;
            default:
                throw new \Exception(sprintf("%s is an incorrect action for %s", $this->getAction(), __CLASS__));
        }
    }
}
