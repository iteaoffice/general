<?php
/**
 * Japaveh Webdesign copyright message placeholder
 *
 * @category    Application
 * @package     Controller
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */
namespace General\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Model\ViewModel;

use General\Service\GeneralService;

/**
 * The index of the system
 *
 * @category    General
 * @package     Controller
 */
class IndexController extends AbstractActionController implements ServiceLocatorAwareInterface
{
    /**
     * @var GeneralService
     */
    protected $generalService;

    /**
     * Show icon of the content type
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function contentTypeIconAction()
    {
        $this->layout(false);
        $response = $this->getResponse();

        $contentType = $this->getGeneralService()->findEntityById('content-type',
            $this->getEvent()->getRouteMatch()->getParam('id')
        );

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")
            ->addHeaderLine("Pragma: public");

        if (!is_null($contentType)) {

            $file = stream_get_contents($contentType->getImage());

            $response->getHeaders()
                ->addHeaderLine('Content-Type: image/gif')
                ->addHeaderLine('Content-Length: ' . (string)strlen($file));

            $response->setContent($file);

            return $response;
        } else {
            $response->getHeaders()
                ->addHeaderLine('Content-Type: image/jpg');
            $response->setStatusCode(404);
            /**
             * $config = $this->getServiceLocator()->get('config');
             * readfile($config['file_config']['upload_dir'] . DIRECTORY_SEPARATOR . 'removed.jpg');
             */
        }
    }

    /**
     * Gateway to the General Service
     *
     * @return GeneralService
     */
    public function getGeneralService()
    {
        return $this->getServiceLocator()->get('general_general_service');
    }
}
