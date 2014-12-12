<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category  General
 * @package   Service
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\Service;

/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category  General
 * @package   Service
 * @author    Johan van der Heide <info@japaveh.nl>
 * @copyright 2004-2014 Japaveh Webdesign
 * @license   http://solodb.net/license.txt proprietary
 * @link      http://solodb.net
 */
interface EmailServiceAwareInterface
{
    /**
     * The email service
     *
     * @param EmailService $emailService
     */
    public function setEmailService(EmailService $emailService);

    /**
     * Get email service
     *
     * @return EmailService
     */
    public function getEmailService();
}
