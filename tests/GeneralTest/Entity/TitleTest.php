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

use General\Entity\Title;
use GeneralTest\Bootstrap;
use Zend\InputFilter\InputFilter;
use Zend\Math\Rand;

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


    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->entityManager = $this->serviceManager->get('doctrine.entitymanager.orm_default');
    }

    /**
     * @return array
     */
    public function provider()
    {
        $title = new Title();
        $title->setName(Rand::getString(4));
        $title->setAttention('attention:' . Rand::getString(4));
        $title->setSalutation('This is the salutation');

        return [
            [$title]
        ];
    }


    public function testCanCreateEntity()
    {
        $title = new Title();
        $this->assertInstanceOf("General\Entity\Title", $title);
        $this->assertNull($title->getId(), 'The "Id" should be null');
        $id = 1;
        $title->setId($id);
        $this->assertTrue(is_array($title->getArrayCopy()));
        $this->assertTrue(is_array($title->populate()));
    }

    public function testMagicGettersAndSetters()
    {
        $title = new Title();
        $title->name = 'test';
        $this->assertEquals('test', $title->name);
    }

    /**
     * @expectedException \Exception
     */
    public function testCannotSetInputFilter()
    {
        $title = new Title();
        $title->setInputFilter(new InputFilter());
    }

    public function testHasFilter()
    {
        $title = new Title();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $title->getInputFilter());
    }

    /**
     * @param Title $title
     *
     * @dataProvider provider
     */
    public function testCanSaveEntityInDatabase(Title $title)
    {
        $this->entityManager->persist($title);
        $this->entityManager->flush();
        $this->assertInstanceOf('General\Entity\Title', $title);
        $this->assertNotNull($title->getId());
        $this->assertNotNull($title->getResourceId());
    }
}
