<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\TaskStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $task = $this->taskWithoutStatus($faker);
            $task->setStatus(TaskStatus::ToDo);

            $manager->persist($task);
        }

        for ($i = 0; $i < 2; $i++) {
            $task = $this->taskWithoutStatus($faker);
            $task->setStatus(TaskStatus::InProgress);

            $manager->persist($task);
        }

        for ($i = 0; $i < 3; $i++) {
            $task = $this->taskWithoutStatus($faker);
            $task->setStatus(TaskStatus::Done);

            $manager->persist($task);
        }

        $manager->flush();
    }

    private function taskWithoutStatus(Faker\Generator $faker): Task
    {
        $task = new Task();
        $nWords = $faker->numberBetween(2, 5);
        $task->setTitle($faker->sentence($nWords));

        $nChars = $faker->numberBetween(70, 200);
        $task->setDescription($faker->text($nChars));

        return $task;
    }
}
