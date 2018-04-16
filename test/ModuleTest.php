<?php
/**
 * ITEA copyright message placeholder
 *
 * @category    ProjectTest
 * @package     Entity
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace GeneralTest\InputFilter;

use General\Controller\Plugin\GetFilter;
use General\InputFilter\Challenge\TypeFilter;
use General\InputFilter\ChallengeFilter;
use General\InputFilter\CountryFilter;
use General\InputFilter\GenderFilter;
use General\InputFilter\TitleFilter;
use General\InputFilter\WebInfoFilter;
use General\Module;
use General\Service\EmailService;
use Testing\Util\AbstractServiceTest;
use Zend\Mvc\Application;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;

/**
 * Class GeneralTest
 *
 * @package GeneralTest\Entity
 */
class ModuleTest extends AbstractServiceTest
{
    public function testCanFindConfiguration(): void
    {
        $module = new Module();
        $config = $module->getConfig();

        $this->assertInternalType('array', $config);
        $this->assertArrayHasKey('service_manager', $config);
        $this->assertArrayHasKey(ConfigAbstractFactory::class, $config);
    }

    /**
     *
     */
    public function testInstantiationOfConfigAbstractFactories(): void
    {
        $module = new Module();
        $config = $module->getConfig();

        $abstractFacories = $config[ConfigAbstractFactory::class] ?? [];

        foreach ($abstractFacories as $service => $dependencies) {

            //Skip the GetFilter
            if (\in_array(
                $service,
                [GetFilter::class, EmailService::class, ChallengeFilter::class, TypeFilter::class, CountryFilter::class,
                 GenderFilter::class, TitleFilter::class, WebInfoFilter::class],
                true
            )
            ) {
                continue;
            }

            $instantiatedDependencies = [];
            foreach ($dependencies as $dependency) {

                if ($dependency === 'Application') {
                    $dependency = Application::class;
                }
                if ($dependency === 'Config') {
                    $dependency = [];
                }
                $instantiatedDependencies[]
                    = $this->getMockBuilder($dependency)->disableOriginalConstructor()->getMock();
            }

            $instance = new $service(...$instantiatedDependencies);

            $this->assertInstanceOf($service, $instance);
        }

    }
}