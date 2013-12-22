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

use General\Entity\Eu;
use General\Entity\Country;

use GeneralTest\Bootstrap;


class EuTest extends \PHPUnit_Framework_TestCase
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
    protected $euData;
    /**
     * @var Eu
     */
    protected $eu;


    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->entityManager = $this->serviceManager->get('doctrine.entitymanager.orm_default');

        $country = $this->entityManager->find("General\Entity\Country", 1);

        $this->euData = array(
            'country' => $country,
            'since' => new \DateTime(),
        );

        $this->eu = new Eu;
    }

    public function testCanCreateEntity()
    {
        $this->assertInstanceOf("General\Entity\Eu", $this->eu);

        $this->assertNull($this->eu->getId(), 'The "Id" should be null');

        $id = 1;
        $this->eu->setId($id);

        $this->assertTrue(is_array($this->eu->getArrayCopy()));
        $this->assertTrue(is_array($this->eu->populate()));
    }

    public function testMagicGettersAndSetters()
    {
        $date = new \DateTime();
        $this->eu->since = $date;
        $this->assertEquals($date, $this->eu->since);
    }

    /**
     * @expectedException \Exception
     */
    public function testCannotSetInputFilter()
    {
        $this->eu->setInputFilter(new InputFilter());
    }

    public function testHasFilter()
    {
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $this->eu->getInputFilter());
    }

    public function testCanSaveEntityInDatabase()
    {
        $hydrator = new DoctrineObject(
            $this->entityManager,
            'General\Entity\Eu'
        );

        $this->eu = $hydrator->hydrate($this->euData, new Eu());
        $this->entityManager->persist($this->eu);
        $this->entityManager->flush();

        //Since we don't save, we give the $eu a virtual id
        $this->eu->setId(1);

        $this->assertInstanceOf('General\Entity\Eu', $this->eu);
        $this->assertNotNull($this->eu->getId());
        $this->assertEquals($this->eu->getSince(), $this->euData['since']);
        $this->assertEquals($this->eu->getCountry()->getCountry(), $this->euData['country']->getCountry());

        $this->assertNotNull($this->eu->getResourceId());
    }
}
