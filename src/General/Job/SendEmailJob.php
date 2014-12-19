<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category  General
 * @package   Factory
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\Job;

use General\Service\EmailService;
use SlmQueue\Job\AbstractJob;

class SendEmailJob extends AbstractJob
{
    /**
     * @var EmailService
     */
    protected $emailService;

    /**
     * @param EmailService $emailService
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     *
     */
    public function execute()
    {
        $email = $this->emailService->create();
        $email->addTo('info@solodb.net');
        $this->emailService->setTemplate("/project/report-item/submit:mail");

        $this->emailService->send();

        print 'test';

    }
}