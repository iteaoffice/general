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

use General\Entity\Gender;
use GeneralTest\Bootstrap;
use Zend\InputFilter\InputFilter;
use Zend\Math\Rand;

class GenderTest extends \PHPUnit_Framework_TestCase
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
        $gender = new Gender();
        $gender->setName(Rand::getString(4));
        $gender->setAttention('attention:' . Rand::getString(4));
        $gender->setSalutation('This is the salutation');

        return [
            [$gender]
        ];
    }

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->entityManager = $this->serviceManager->get('Doctrine\ORM\EntityManager');
    }

    public function testCanCreateEntity()
    {
        $gender = new Gender();
        $this->assertInstanceOf("General\Entity\Gender", $gender);
        $this->assertNull($gender->getId(), 'The "Id" should be null');
        $id = 1;
        $gender->setId($id);
        $this->assertTrue(is_array($gender->getArrayCopy()));
        $this->assertTrue(is_array($gender->populate()));
    }

    public function testMagicGettersAndSetters()
    {
        $gender = new Gender();
        $gender->name = 'test';
        $this->assertEquals('test', $gender->name);
    }

    /**
     * @expectedException \Exception
     */
    public function testCannotSetInputFilter()
    {
        $gender = new Gender();
        $gender->setInputFilter(new InputFilter());
    }

    public function testHasFilter()
    {
        $gender = new Gender();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $gender->getInputFilter());
    }

    /**
     * @param Gender $gender
     *
     * @dataProvider provider
     */
    public function testCanSaveEntityInDatabase(Gender $gender)
    {
        $this->entityManager->persist($gender);
        $this->entityManager->flush();
        $this->assertInstanceOf('General\Entity\Gender', $gender);
        $this->assertNotNull($gender->getId());

    }
}
