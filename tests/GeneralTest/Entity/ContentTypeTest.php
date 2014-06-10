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

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

use General\Entity\ContentType;

use GeneralTest\Bootstrap;

class ContentTypeTest extends \PHPUnit_Framework_TestCase
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
    protected $contentTypeData;
    /**
     * @var ContentType
     */
    protected $contentType;

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->entityManager  = $this->serviceManager->get('doctrine.entitymanager.orm_default');

        $this->contentTypeData = array(
            'description' => 'description',
            'contentType' => 'This is a content type',
            'extension'   => 'abc',
            'image'       => 'blob',
        );

        $this->contentType = new ContentType;
    }

    public function testCanCreateEntity()
    {
        $this->assertInstanceOf("General\Entity\ContentType", $this->contentType);

        $this->assertNull($this->contentType->getId(), 'The "Id" should be null');

        $id = 1;
        $this->contentType->setId($id);
    }

    public function testCanSaveEntityInDatabase()
    {
        $hydrator = new DoctrineObject(
            $this->entityManager,
            'General\Entity\ContentType'
        );

        $this->contentType = $hydrator->hydrate($this->contentTypeData, new ContentType());
        $this->entityManager->persist($this->contentType);
        $this->entityManager->flush();

        //Since we don't save, we give the $contentType a virtual id
        $this->contentType->setId(1);

        $this->assertInstanceOf('General\Entity\ContentType', $this->contentType);
        $this->assertNotNull($this->contentType->getId());
        $this->assertEquals($this->contentType->getDescription(), $this->contentTypeData['description']);
        $this->assertEquals($this->contentType->getContentType(), $this->contentTypeData['contentType']);
        $this->assertEquals($this->contentType->getExtension(), $this->contentTypeData['extension']);
        $this->assertEquals($this->contentType->getImage(), $this->contentTypeData['image']);
    }
}
