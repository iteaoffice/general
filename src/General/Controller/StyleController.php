<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    Application
 * @package     Controller
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * The index of the system
 *
 * @category    Application
 * @package     Controller
 */
class StyleController extends AbstractActionController implements ServiceLocatorAwareInterface
{

    /**
     * Index of the Index
     */
    public function displayAction()
    {
        $this->layout(false);

        $response = $this->getResponse();
        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")
            ->addHeaderLine("Pragma: public");

        $options            = $this->getServiceLocator()->get('general_module_options');
        $requestedFileFound = false;
        foreach ($options->getStyleLocations() as $location) {
            $requestedFile = $location . DIRECTORY_SEPARATOR
                . $options->getImageLocation() . DIRECTORY_SEPARATOR
                . $this->getEvent()->getRouteMatch()->getParam('source');

            if (!$requestedFileFound && file_exists($requestedFile)) {
                $requestedFileFound = true;
                break;
            }
        }

        if (!$requestedFileFound || is_null($this->getEvent()->getRouteMatch()->getParam('source'))) {
            foreach ($options->getStyleLocations() as $location) {
                $requestedFile = $location . DIRECTORY_SEPARATOR
                    . $options->getImageLocation() . DIRECTORY_SEPARATOR
                    . $options->getImageNotFound();

                if (file_exists($requestedFile)) {
                    break;
                }
            }
        }

        /**
         * Create a cache-version of the file
         */
        $cacheDir = __DIR__ . '/../../../../../../public/assets/' .
            DEBRANOVA_HOST . DIRECTORY_SEPARATOR . 'style' . DIRECTORY_SEPARATOR . 'image';
        if (!file_exists($cacheDir . DIRECTORY_SEPARATOR . $this->getEvent()->getRouteMatch()->getParam('source'))) {
            //Save a copy of the file in the caching-folder
            file_put_contents(
                $cacheDir . DIRECTORY_SEPARATOR . $this->getEvent()->getRouteMatch()->getParam('source'),
                file_get_contents($requestedFile)
            );
        }

        $response->getHeaders()
            ->addHeaderLine('Content-Type: image/jpg')
            ->addHeaderLine('Content-Length: ' . (string) filesize($requestedFile));

        $response->setContent(file_get_contents($requestedFile));

        return $response;
    }
}
