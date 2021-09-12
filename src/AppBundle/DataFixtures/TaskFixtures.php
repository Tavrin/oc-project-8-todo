<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TaskFixtures extends Fixture implements DependentFixtureInterface, ORMFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ]
        ;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $task = new Task();
            $task->setUser($this->getReference('user'));
            $task->setTitle($faker->sentence());
            $task->setContent($faker->paragraph(3));
            $task->setCreatedAt($faker->dateTimeBetween('-12 months', 'now'));
            $manager->persist($task);
        }



        $manager->flush();
    }
}