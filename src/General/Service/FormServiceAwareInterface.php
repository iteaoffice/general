<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  Organisation
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\Service;

use Organisation\Service\FormService;

interface FormServiceAwareInterface
{
    /**
     * Get formService.
     *
     * @return FormService.
     */
    public function getFormService();

    /**
     * Set formService.
     *
     * @param FormService $formService
     */
    public function setFormService($formService);
}
