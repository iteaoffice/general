<?php
namespace GeneralTest\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadTitleData implements FixtureInterface
{
    /**
     * Load the Title
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $title = new \General\Entity\Title();
        $title->setName("This is the title");
        $title->setAttention("attention for title");
        $title->setSalutation("Salutation for title");

        $manager->persist($title);
        $manager->flush();
    }
}
