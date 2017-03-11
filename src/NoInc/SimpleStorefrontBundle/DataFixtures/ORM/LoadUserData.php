<?php

namespace NoInc\SimpleStorefrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NoInc\SimpleStorefrontBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin@noinc.com');
        $userAdmin->setEmail('admin@noinc.com');
        $userAdmin->setPlainPassword('test123');
        $userAdmin->setCapital(1000);
        $userAdmin->setEnabled(true);
        $userAdmin->addRole("ROLE_ADMIN");

        $manager->persist($userAdmin);
        $manager->flush();
        
        $userGuest1 = new User();
        $userGuest1->setUsername('guest@noinc.com');
        $userGuest1->setEmail('guest@noinc.com');
        $userGuest1->setPlainPassword('test123');
        $userGuest1->setCapital(100);
        $userGuest1->setEnabled(true);
        
        $manager->persist($userGuest1);
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 1;
    }
}