<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    Twitter
 * @package     View
 * @subpackage  Helper
 * @author      Andre Hebben <andre.hebben@artemis-ia.eu>
 * @copyright   Copyright (c) 2007-2014 ARTEMIS-IA (http://artemis-ia.eu)
 */
namespace General\View\Helper;

use Content\Entity\Content;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\HelperPluginManager;

/**
 * Class TwitterHandler
 * @package Twitter\View\Helper
 */
class TwitterHandler extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * @var HelperPluginManager
     */
    protected $serviceLocator;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator->getServiceLocator();
    }

    /**
     * @param Content $content
     *
     * @return string
     */
    public function __invoke(Content $content)
    {
        $this->extractContentParam($content);
        switch ($content->getHandler()->getHandler()) {
            case 'twitter':
                //generate handler through this
                //append the java script
                //generate through partial
                break;
        }
    }

}
