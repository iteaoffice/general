<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */

namespace General\Factory;

use General\Job\SendEmailJob;
use General\Service\EmailService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SendEmailJobFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $sl
     *
     * @return SendEmailJob
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $emailService = $sl->getServiceLocator()->get(EmailService::class);

        return new SendEmailJob($emailService);
    }
}
