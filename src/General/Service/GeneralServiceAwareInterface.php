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
interface GeneralServiceAwareInterface
{
    /**
     * The general service
     *
     * @param GeneralService $generalService
     */
    public function setGeneralService(GeneralService $generalService);

    /**
     * Get general service
     *
     * @return GeneralService
     */
    public function getGeneralService();
}
