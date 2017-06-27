<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */
declare(strict_types=1);

namespace General\Acl\Factory;

use Admin\Service\AdminService;
use General\Acl\Assertion\AssertionAbstract;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AssertionFactory
 *
 * @package General\Acl\Factory
 */
class AssertionFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param                    $requestedName
     * @param array|null $options
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AssertionAbstract
    {
        /** @var AssertionAbstract $assertion */
        $assertion = new $requestedName($options);
        $assertion->setServiceLocator($container);

        /** @var AdminService $adminService */
        $adminService = $container->get(AdminService::class);
        $assertion->setAdminService($adminService);

        //Inject the logged in user if applicable
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get('Application\Authentication\Service');
        if ($authenticationService->hasIdentity()) {
            $assertion->setContact($authenticationService->getIdentity());
        }

        return $assertion;
    }
}
