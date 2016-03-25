<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * PHP Version 5
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   2004-2016 ITEA Office
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

namespace General\View\Factory;

use General\View\Helper\LinkAbstract;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\InvalidServiceException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for instantiating classes with no dependencies or which accept a single array.
 *
 * The InvokableFactory can be used for any class that:
 *
 * - has no constructor arguments;
 * - accepts a single array of arguments via the constructor.
 *
 * It replaces the "invokables" and "invokable class" functionality of the v2
 * service manager, and can also be used in v2 code for forwards compatibility
 * with v3.
 */
final class LinkInvokableFactory implements FactoryInterface
{
    /**
     * Options to pass to the constructor (when used in v2), if any.
     *
     * @param null|array
     */
    private $creationOptions;

    /**
     * @param null|array|\Traversable $creationOptions
     *
     * @throws InvalidServiceException if $creationOptions cannot be coerced to
     *     an array.
     */
    public function __construct($creationOptions = null)
    {
        if (null === $creationOptions) {
            return;
        }

        $this->setCreationOptions($creationOptions);
    }

    /**
     * Create an instance of the requested class name.
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     *
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var LinkAbstract $linkHelper */
        $linkHelper = (null === $options) ? new $requestedName : new $requestedName($options);
        $linkHelper->setServiceLocator($container);

        return $linkHelper;
    }

    /**
     * Create an instance of the named service.
     *
     * First, it checks if `$canonicalName` resolves to a class, and, if so, uses
     * that value to proxy to `__invoke()`.
     *
     * Next, if `$requestedName` is non-empty and resolves to a class, this
     * method uses that value to proxy to `__invoke()`.
     *
     * Finally, if the above each fail, it raises an exception.
     *
     * The approach above is performed as version 2 has two distinct behaviors
     * under which factories are invoked:
     *
     * - If an alias was used, $canonicalName is the resolved name, and
     *   $requestedName is the service name requested, in which case $canonicalName
     *   is likely the qualified class name;
     * - Otherwise, $canonicalName is the normalized name, and $requestedName
     *   is the original service name requested (typically the qualified class name).
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param null|string             $canonicalName
     * @param null|string             $requestedName
     *
     * @return object
     * @throws InvalidServiceException
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null)
    {
        if (class_exists($canonicalName)) {
            return $this($serviceLocator, $canonicalName, $this->creationOptions);
        }

        if (is_string($requestedName) && class_exists($requestedName)) {
            return $this($serviceLocator, $requestedName, $this->creationOptions);
        }

        throw new InvalidServiceException(sprintf(
            '%s requires that the requested name is provided on invocation; please update your tests or consuming container',
            __CLASS__
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setCreationOptions(array $creationOptions)
    {
        $this->creationOptions = $creationOptions;
    }
}
