<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\Controller;

/**
 * The index of the system.
 *
 * @category Application
 */
class StyleController extends GeneralAbstractController
{
    /**
     * Index of the Index.
     */
    public function displayAction()
    {
        $requestedFile = '';
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")->addHeaderLine("Pragma: public");


        $requestedFileFound = false;
        foreach ($this->getModuleOptions()->getStyleLocations() as $location) {
            $requestedFile = $location . DIRECTORY_SEPARATOR . $this->getModuleOptions()->getImageLocation()
                . DIRECTORY_SEPARATOR . $this->params('source');
            if (!$requestedFileFound && file_exists($requestedFile)) {
                $requestedFileFound = true;
                break;
            }
        }
        if (!$requestedFileFound
            || is_null($this->params('source'))
        ) {
            foreach ($this->getModuleOptions()->getStyleLocations() as $location) {
                $requestedFile = $location . DIRECTORY_SEPARATOR . $this->getModuleOptions()->getImageLocation()
                    . DIRECTORY_SEPARATOR . $this->getModuleOptions()->getImageNotFound();
                if (file_exists($requestedFile)) {
                    break;
                }
            }
        }
        /*
         * Create a cache-version of the file
         */
        $cacheDir = __DIR__ . '/../../../../../public/assets/' . (defined("ITEAOFFICE_HOST") ? ITEAOFFICE_HOST
                : 'test') . DIRECTORY_SEPARATOR . 'style' . DIRECTORY_SEPARATOR . 'image';
        if (!file_exists($cacheDir . DIRECTORY_SEPARATOR . $this->params('source'))) {
            //Save a copy of the file in the caching-folder
            file_put_contents(
                $cacheDir . DIRECTORY_SEPARATOR . $this->params('source'),
                file_get_contents($requestedFile)
            );
        }
        $response->getHeaders()->addHeaderLine('Content-Type: image/jpg')->addHeaderLine('Content-Length: '
            . (string)filesize($requestedFile));
        $response->setContent(file_get_contents($requestedFile));

        return $response;
    }
}
