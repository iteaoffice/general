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
use Zend\View\Model\ViewModel;

/**
 * The index of the system
 *
 * @category    Application
 * @package     Controller
 */
class IndexController extends AbstractActionController
{

    /**
     * Index of the Index
     */
    public function indexAction()
    {
        return new ViewModel();
    }
}
