<?php
/**
 * ITEA copyright message placeholder
 *
 * @category    ContactTest
 * @package     Entity
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace TitlealTest\Entity;

use Zend\InputFilter\InputFilter;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

use General\Entity\Title;
use GeneralTest\Bootstrap;

class TitleTest extends \PHPUnit_Framework_TestCase
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
    protected $titleData;
    /**
     * @var Title
     */
    protected $title;

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->entityManager = $this->serviceManager->get('doctrine.entitymanager.orm_default');

        $this->titleData = array(
            'name' => 'This is the name of the title',
            'attention' => 'This is the attention',
            'salutation' => 'This is the salutation'
        );

        $this->title = new Title;
    }

    public function testCanCreateEntity()
    {
        $this->assertInstanceOf("General\Entity\Title", $this->title);

        $this->assertNull($this->title->getId(), 'The "Id" should be null');

        $id = 1;
        $this->title->setId($id);

        $this->assertTrue(is_array($this->title->getArrayCopy()));
        $this->assertTrue(is_array($this->title->populate()));
    }

    public function testMagicGettersAndSetters()
    {
        $this->title->name = 'test';
        $this->assertEquals('test', $this->title->name);
    }

    /**
     * @expectedException \Exception
     */
    public function testCannotSetInputFilter()
    {
        $this->title->setInputFilter(new InputFilter());
    }

    public function testHasFilter()
    {
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $this->title->getInputFilter());
    }

    public function testCanSaveEntityInDatabase()
    {
        $hydrator = new DoctrineObject(
            $this->entityManager,
            'General\Entity\Title'
        );

        $this->title = $hydrator->hydrate($this->titleData, new Title());
        $this->entityManager->persist($this->title);
        $this->entityManager->flush();

        //Since we don't save, we give the $title a virtual id
        $this->title->setId(1);

        $this->assertInstanceOf('General\Entity\Title', $this->title);
        $this->assertNotNull($this->title->getId());
        $this->assertEquals($this->title->getName(), $this->titleData['name']);
        $this->assertEquals($this->title->getAttention(), $this->titleData['attention']);
        $this->assertEquals($this->title->getSalutation(), $this->titleData['salutation']);

        $this->assertNotNull($this->title->getResourceId());

        $this->entityManager->remove($this->title);
    }

}
