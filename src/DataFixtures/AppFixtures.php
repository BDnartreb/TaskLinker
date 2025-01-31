<?php

namespace App\DataFixtures;

use App\Factory\EmployeeFactory;
use App\Factory\ProjectFactory;
use App\Factory\TaskFactory;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        EmployeeFactory::createMany(3);
        TaskFactory::createMany(3);
        ProjectFactory::createMany(3);

        $manager->flush();
    }
}
