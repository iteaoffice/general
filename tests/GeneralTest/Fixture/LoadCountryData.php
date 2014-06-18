<?php
namespace GeneralTest\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCountryData implements FixtureInterface
{
    /**
     * Load the Contact
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $country = new \General\Entity\Country();
        $country->setCountry('country');
        $country->setCd('cd');
        $country->setNumcode(100);
        $country->setIso3('CCD');
        $manager->persist($country);
        $manager->flush();
    }
}
