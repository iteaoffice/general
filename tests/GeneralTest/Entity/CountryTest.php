<?php
/**
 * ITEA copyright message placeholder
 *
 * @category    ContactTest
 * @package     Entity
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 ITEA
 */
namespace CountryalTest\Entity;

use Zend\InputFilter\InputFilter;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

use General\Entity\Country;
use GeneralTest\Bootstrap;


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
     * @var array
     */
    protected $countryData = array(
        'country' => 'This is a name of the country',
        'cd' => 'CD-Code',
        'iso3' => 'BEL',
        'numcode' => '123',
        'vat' => NULL
    );

    /**
     * @var Country
     */
    protected $country;


    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->entityManager = $this->serviceManager->get('doctrine.entitymanager.orm_default');

        $this->country = new Country;
    }

    public function testCanCreateEntity()
    {
        $this->assertInstanceOf("General\Entity\Country", $this->country);

        $this->assertNull($this->country->getId(), 'The "Id" should be null');

        $id = 1;
        $this->country->setId($id);

        $this->assertTrue(is_array($this->country->getArrayCopy()));
        $this->assertTrue(is_array($this->country->populate()));
    }

    public function testMagicGettersAndSetters()
    {
        $this->country->country = 'test';
        $this->assertEquals('test', $this->country->country);
    }

    /**
     * @expectedException \Exception
     */
    public function testCannotSetInputFilter()
    {
        $this->country->setInputFilter(new InputFilter());
    }

    public function testHasFilter()
    {
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $this->country->getInputFilter());
    }


    public function testCanSaveEntityInDatabase()
    {
        $hydrator = new DoctrineObject(
            $this->entityManager,
            'General\Entity\Country'
        );

        $this->country = $hydrator->hydrate($this->countryData, new Country());
        $this->entityManager->persist($this->country);
        $this->entityManager->flush();

        $this->assertInstanceOf('General\Entity\Country', $this->country);
        $this->assertNotNull($this->country->getId());
        $this->assertEquals($this->country->getCountry(), $this->countryData['country'], 'The country name of the saved entity should be the same as the original name');
        $this->assertEquals($this->country->getCd(), $this->countryData['cd'], 'The cd of the saved entity should be the same as the original name');
        $this->assertEquals($this->country->getIso3(), $this->countryData['iso3'], 'The iso3 of the saved entity should be the same as the original name');
        $this->assertEquals($this->country->getNumcode(), $this->countryData['numcode'], 'The numcode of the saved entity should be the same as the original name');
        $this->assertEquals($this->country->getVat(), $this->countryData['vat'], 'The country_vat of the saved entity should be the same as the original name');

        $this->assertNotNull($this->country->getResourceId());

    }

    public function testToString()
    {
        $this->country->country = $this->countryData['country'];
        $this->assertEquals((string)$this->country, $this->countryData['country']);
    }

}
