<?php
// src/ApiBundle/DataFixtures/ORM/LoadManager.php

namespace ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ModelBundle\Entity\Manager;

class LoadManager implements FixtureInterface
{
    public function load(ObjectManager $obj_manager)
    {
        $manager = new Manager();
        $manager->setName("Rob");
        $manager->setApiKey("4KJqRxUKQQwuKPbM");
        $manager->setActive(True);

        $obj_manager->persist($manager);
        $obj_manager->flush();
    }
}
