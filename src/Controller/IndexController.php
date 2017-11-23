<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Controller;

use General\Entity\ContentType;

/**
 * The index of the system.
 *
 * @category General
 */
class IndexController extends GeneralAbstractController
{


    /**
     * Redirect an old project to a new project.
     */
    public function codeAction()
    {
        if (\is_null($this->params('cd'))) {
            return $this->notFoundAction();
        }

        $country = $this->getGeneralService()->findCountryByCD($this->params('cd'));

        if (\is_null($country)) {
            return $this->notFoundAction();
        }

        return $this->redirect()->toRoute(
            'route-' . $country->get('underscore_entity_name'),
            [
                'docRef' => $country->getDocRef(),
            ]
        )->setStatusCode(301);
    }
}
