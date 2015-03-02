<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */

namespace General\Controller;

use General\Service\GeneralService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * The index of the system.
 *
 * @category General
 */
class IndexController extends AbstractActionController
{
    /**
     * @var GeneralService
     */
    protected $generalService;

    /**
     * Show icon of the content type.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function contentTypeIconAction()
    {
        $response = $this->getResponse();
        $contentType = $this->getGeneralService()->findEntityById(
            'content-type',
            $this->getEvent()->getRouteMatch()->getParam('id')
        );
        if (is_null($contentType)) {
            return $this->notFoundAction();
        }
        $response->getHeaders()
            ->addHeaderLine('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")
            ->addHeaderLine("Pragma: public");
        $file = stream_get_contents($contentType->getImage());
        $response->getHeaders()
            ->addHeaderLine('Content-Type: image/gif')
            ->addHeaderLine('Content-Length: '.(string) strlen($file));
        $response->setContent($file);

        return $response;
    }

    /**
     * Display an icon of a country.
     */
    public function countryFlagAction()
    {
        $country = $this->getGeneralService()->findCountryByIso3(
            strtolower($this->getEvent()->getRouteMatch()->getParam('iso3'))
        );
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
        if (!file_exists($country->getFlag()->getCacheFileName())) {
            //Save a copy of the file in the caching-folder
            file_put_contents($country->getFlag()->getCacheFileName(), $file);
        }

        $response->getHeaders()
            ->addHeaderLine('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")
            ->addHeaderLine("Pragma: public")
            ->addHeaderLine('Content-Type: image/png')
            ->addHeaderLine('Content-Length: '.(string) strlen($file));
        $response->setContent($file);

        return $response;
    }

    /**
     * Redirect an old project to a new project.
     */
    public function codeAction()
    {
        $country = $this->getGeneralService()->findCountryByCD(
            $this->getEvent()->getRouteMatch()->getParam('cd')
        );

        return $this->redirect()->toRoute(
            'route-'.$country->get('underscore_full_entity_name'),
            ['docRef' => $country->getDocRef()]
        )->setStatusCode(301);
    }

    /**
     * Gateway to the General Service.
     *
     * @return GeneralService
     */
    public function getGeneralService()
    {
        return $this->getServiceLocator()->get(GeneralService::class);
    }
}
