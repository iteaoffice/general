<?php
namespace GeneralTest\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use General\Entity\WebInfo;

class LoadWebInfoData implements FixtureInterface
{
    /**
     * Load the Title
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $webInfo = new WebInfo();
        $webInfo->setInfo("/project/invite:mail_new");
        $webInfo->setSubject('New project invite');
        $webInfo->setContent('[deeplink]');
        $manager->persist($webInfo);
        $manager->flush();
        $webInfo = new WebInfo();
        $webInfo->setInfo("/project/invite:mail_existing");
        $webInfo->setSubject('Existing project invite');
        $webInfo->setContent('[deeplink]');
        $manager->persist($webInfo);
        $manager->flush();
    }
}
