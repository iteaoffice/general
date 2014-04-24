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

use Zend\InputFilter\InputFilter;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

use General\Entity\Gender;
use GeneralTest\Bootstrap;

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
     * @var array
     */
    protected $genderData;
    /**
     * @var Gender
     */
    protected $gender;

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->entityManager  = $this->serviceManager->get('doctrine.entitymanager.orm_default');

        $this->genderData = array(
            'name'       => 'This is the name of the gender',
            'attention'  => 'This is the attention',
            'salutation' => 'This is the salutation'
        );

        $this->gender = new Gender;
    }

    public function testCanCreateEntity()
    {
        $this->assertInstanceOf("General\Entity\Gender", $this->gender);

        $this->assertNull($this->gender->getId(), 'The "Id" should be null');

        $id = 1;
        $this->gender->setId($id);

        $this->assertTrue(is_array($this->gender->getArrayCopy()));
        $this->assertTrue(is_array($this->gender->populate()));
    }

    public function testMagicGettersAndSetters()
    {
        $this->gender->name = 'test';
        $this->assertEquals('test', $this->gender->name);
    }

    /**
     * @expectedException \Exception
     */
    public function testCannotSetInputFilter()
    {
        $this->gender->setInputFilter(new InputFilter());
    }

    public function testHasFilter()
    {
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $this->gender->getInputFilter());
    }

    public function testCanSaveEntityInDatabase()
    {
        $hydrator = new DoctrineObject(
            $this->entityManager,
            'General\Entity\Gender'
        );

        $this->gender = $hydrator->hydrate($this->genderData, new Gender());

        $this->assertEquals((string)$this->gender, $this->genderData['attention']);

        $this->entityManager->persist($this->gender);
        $this->entityManager->flush();

        $this->assertInstanceOf('General\Entity\Gender', $this->gender);
        $this->assertNotNull($this->gender->getId());
        $this->assertEquals(
            $this->gender->getName(),
            $this->genderData['name'],
            'The name of the saved entity should be the same as the original name'
        );
        $this->assertEquals(
            $this->gender->getAttention(),
            $this->genderData['attention'],
            'The name of the saved entity should be the same as the original name'
        );
        $this->assertEquals(
            $this->gender->getSalutation(),
            $this->genderData['salutation'],
            'The name of the saved entity should be the same as the original name'
        );

        $this->assertNotNull($this->gender->getResourceId());

//        $this->entityManager->remove($this->gender);
//        $this->entityManager->flush();
    }
}
