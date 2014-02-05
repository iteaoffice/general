<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    Project
 * @package     View
 * @subpackage  Helper
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */

namespace General\View\Helper;

use Zend\View\HelperPluginManager;
use Zend\View\Helper\AbstractHelper;

use General\Service\GeneralService;

/**
 * Class VersionServiceProxy
 * @package General\View\Helper
 */
class GeneralServiceProxy extends AbstractHelper
{
    /**
     * @var GeneralService
     */
    protected $generalService;

    /**
     * @param HelperPluginManager $helperPluginManager
     */
    public function __construct(HelperPluginManager $helperPluginManager)
    {
        $this->generalService = $helperPluginManager->getServiceLocator()->get('general_general_service');
    }

    /**
     * @return GeneralService
     */
    public function __invoke()
    {
        return $this->generalService;
    }
}
