<?php
namespace GeneralTest\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadContentTypeData implements FixtureInterface
{
    /**
     * Load the Contact
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $contentType = new \General\Entity\ContentType();
        $contentType->setDescription('This is another description');
        $contentType->setContentType('application/pdf');
        $contentType->setExtension('pdf');
        $contentType->setImage(file_get_contents(__DIR__ . '/../../assets/img/image_not_found.jpg'));
        $manager->persist($contentType);
        $manager->flush();
    }
}
