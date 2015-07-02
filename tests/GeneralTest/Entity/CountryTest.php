<?php
/**
 * ITEA copyright message placeholder
 *
 * @category    ContactTest
 * @package     Entity
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace GeneralTest\Entity;

use General\Entity\Country;
use GeneralTest\Bootstrap;
use Zend\InputFilter\InputFilter;

class CountryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceManager;
    /**
     * @var \Doctrine\ORM\EntityManager;
     */
    protected $entityManager;

    /**
     * @return array
     */
    public function provider()
    {
        $country = new Country();
        $country->setCountry('Testonia');
        $country->setCd('TS');
        $country->setIso3('TST');
        $country->setNumcode('1234');

        return [
            [$country]
        ];
    }

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->entityManager = $this->serviceManager->get('Doctrine\ORM\EntityManager');
    }

    public function testCanCreateEntity()
    {
        $country = new Country();
        $this->assertInstanceOf("General\Entity\Country", $country);
        $this->assertNull($country->getId(), 'The "Id" should be null');
        $id = 1;
        $country->setId($id);
        $this->assertTrue(is_array($country->getArrayCopy()));
        $this->assertTrue(is_array($country->populate()));
    }

    public function testMagicGettersAndSetters()
    {
        $country = new Country();
        $country->country('test');
        $this->assertEquals('test', $country->country);
    }

    /**
     * @expectedException \Exception
     */
    public function testCannotSetInputFilter()
    {
        $country = new Country();
        $country->setInputFilter(new InputFilter());
    }

    public function testHasFilter()
    {
        $country = new Country();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $country->getInputFilter());
    }

    /**
     * @param Country $country
     *
     * @dataProvider provider
     */
    public function testCanSaveEntityInDatabase(Country $country)
    {
        $this->entityManager->persist($country);
        $this->entityManager->flush();
        $this->assertInstanceOf('General\Entity\Country', $country);
        $this->assertNotNull($country->getId());

        $this->assertNotNull($country->getResourceId());
    }

    public function testToString()
    {
        $country = new Country();
        $country->setCountry('test');
        $this->assertEquals((string) $country, 'test');
    }
}
