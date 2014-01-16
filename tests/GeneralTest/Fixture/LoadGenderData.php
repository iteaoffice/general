<?php
namespace GeneralTest\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadGenderData implements FixtureInterface
{
    /**
     * Load the Gender
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $gender = new \General\Entity\Gender();
        $gender->setName("This is the gender");
        $gender->setAttention("attention for gender");
        $gender->setSalutation("Salutation for gender");

        $manager->persist($gender);
        $manager->flush();
    }
}
