<?php
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
