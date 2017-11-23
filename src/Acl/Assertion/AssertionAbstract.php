<?php
/**
 * ITEA Office all rights reserved
 *
 * @category   Project
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license    https://itea3.org/license.txt proprietary
 *
 * @link       https://itea3.org
 */

declare(strict_types=1);

namespace General\Acl\Assertion;

use Admin\Service\AdminService;
use Contact\Entity\Contact;
use Interop\Container\ContainerInterface;
use Zend\Http\Request;
use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AssertionAbstract
 * @package General\Acl\Assertion
 */
abstract class AssertionAbstract implements AssertionInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    /**
     * @var Contact
     */
    protected $contact;
    /**
     * @var AdminService
     */
    protected $adminService;
    /**
     * @var string
     */
    protected $privilege;
    /**
     * @var array
     */
    protected $accessRoles = [];

    /**
     * @return string
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * @param string $privilege
     *
     * @return AssertionAbstract
     */
    public function setPrivilege($privilege): AssertionAbstract
    {
        /**
         * When the privilege is_null (not given by the isAllowed helper), get it from the routeMatch
         */
        if (\is_null($privilege) && $this->hasRouteMatch()) {
            $this->privilege = $this->getRouteMatch()
                ->getParam('privilege', $this->getRouteMatch()->getParam('action'));
        } else {
            $this->privilege = $privilege;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasRouteMatch(): bool
    {
        return !\is_null($this->getRouteMatch());
    }

    /**
     * @return RouteMatch
     */
    public function getRouteMatch(): ?RouteMatch
    {
        return $this->getServiceLocator()->get("Application")->getMvcEvent()->getRouteMatch();
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator(): ContainerInterface
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface|ContainerInterface $serviceLocator
     *
     * @return AssertionAbstract
     */
    public function setServiceLocator($serviceLocator): AssertionAbstract
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        if (!\is_null($id = $this->getRequest()->getPost('id'))) {
            return (int)$id;
        }
        if (\is_null($this->getRouteMatch())) {
            return null;
        }
        if (!\is_null($id = $this->getRouteMatch()->getParam('id'))) {
            return (int)$id;
        }

        return null;
    }

    /**
     * Proxy to the original request object to handle form.
     *
     * @return Request
     */
    public function getRequest(): ?Request
    {
        return $this->getServiceLocator()->get('application')->getMvcEvent()->getRequest();
    }

    /**
     * Returns true when a role or roles have access.
     *
     * @param $roles
     *
     * @return boolean
     */
    protected function rolesHaveAccess($roles): bool
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        $roles = array_map('strtolower', $roles);

        foreach ($this->getAccessRoles() as $access) {
            if (\in_array(strtolower($access), $roles, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getAccessRoles(): array
    {
        if (empty($this->accessRoles) && $this->hasContact()) {
            $this->accessRoles = $this->getAdminService()->findAccessRolesByContactAsArray($this->getContact());
        }

        return $this->accessRoles;
    }

    /**
     * @return bool
     */
    public function hasContact(): bool
    {
        return !$this->getContact()->isEmpty();
    }

    /**
     * @return Contact
     */
    public function getContact(): Contact
    {
        if (\is_null($this->contact)) {
            $this->contact = new Contact();
        }

        return $this->contact;
    }

    /**
     * @param Contact $contact
     *
     * @return AssertionAbstract
     */
    public function setContact($contact): AssertionAbstract
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return AdminService
     */
    public function getAdminService(): AdminService
    {
        return $this->adminService;
    }

    /**
     * @param AdminService $adminService
     *
     * @return AssertionAbstract
     */
    public function setAdminService($adminService): AssertionAbstract
    {
        $this->adminService = $adminService;

        return $this;
    }
}
