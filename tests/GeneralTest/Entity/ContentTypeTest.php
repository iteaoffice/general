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

use General\Entity\ContentType;
use GeneralTest\Bootstrap;
use Zend\Math\Rand;

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
     *
     */
    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->entityManager = $this->serviceManager->get('Doctrine\ORM\EntityManager');
    }

    /**
     * @return array
     */
    public function provider()
    {
        $contentType = new ContentType();
        $contentType->setContentType('test/' . Rand::getString(12));
        $contentType->setDescription('This is the description ' . Rand::getString(12));
        $contentType->setExtension(Rand::getString(4));

        return [
            [$contentType]
        ];
    }

    public function testCanCreateEntity()
    {
        $contentType = new ContentType();
        $this->assertInstanceOf("General\Entity\ContentType", $contentType);
        $this->assertNull($this->contentType->getId(), 'The "Id" should be null');
        $id = 1;
        $contentType->setId($id);
        $this->assertNotNull($this->contentType->getId(), 'The "Id" should not be null');
    }

    /**
     * @param ContentType $type
     *
     * @dataProvider provider
     */
    public function testCanSaveEntityInDatabase(ContentType $type)
    {
        $this->entityManager->persist($type);
        $this->entityManager->flush();
        //Since we don't save, we give the $contentType a virtual id
        $this->contentType->setId(1);
        $this->assertInstanceOf('General\Entity\ContentType', $this->contentType);
        $this->assertNotNull($type->getId());
    }
}
