<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

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
     * Show icon of the content type.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function contentTypeIconAction()
    {
        $response = $this->getResponse();
        /** @var ContentType $contentType */
        $contentType = $this->getGeneralService()->findEntityById(ContentType::class, $this->params('id'));
        if (is_null($contentType)) {
            return $this->notFoundAction();
        }
        $response->getHeaders()->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
                 ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")->addHeaderLine("Pragma: public");
        $file = stream_get_contents($contentType->getImage());
        $response->getHeaders()->addHeaderLine('Content-Type: image/gif')->addHeaderLine(
            'Content-Length: '
            . (string)strlen($file)
        );
        $response->setContent($file);

        return $response;
    }

    /**
     * Display an icon of a country.
     */
    public function countryFlagAction()
    {
        $country  = $this->getGeneralService()->findCountryByIso3(strtolower($this->params('iso3')));
        $response = $this->getResponse();
        /*
         * Return the response when no iso3 can be found
         */
        if (is_null($country)) {
            return $response;
        }
        $file = stream_get_contents($country->getFlag()->getObject());
        /*
         * Create a cache-version of the file
         */
        if (! file_exists($country->getFlag()->getCacheFileName())) {
            //Save a copy of the file in the caching-folder
            file_put_contents($country->getFlag()->getCacheFileName(), $file);
        }

        $response->getHeaders()->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
                 ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")->addHeaderLine("Pragma: public")
                 ->addHeaderLine('Content-Type: image/png')->addHeaderLine('Content-Length: ' . (string)strlen($file));
        $response->setContent($file);

        return $response;
    }

    /**
     * Redirect an old project to a new project.
     */
    public function codeAction()
    {
        if (is_null($this->params('cd'))) {
            return $this->notFoundAction();
        }

        $country = $this->getGeneralService()->findCountryByCD($this->params('cd'));

        if (is_null($country)) {
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
