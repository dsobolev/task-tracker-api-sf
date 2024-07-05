<?php

namespace App\Tests;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testCreateRouteRejectsEmptyRequest(): void
    {
        $client = static::createClient();
        $client->request('POST', 'tasks'); // empty payload

        $this->assertResponseStatusCodeSame(400);
    }

    public function testCreateWhenTaskTitleIsNotProvided(): void
    {
        $client = static::createClient();
        $client->request('POST', 'tasks', [
            // no title
            'description' => 'Test description',
        ]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testEditWhenEntityNotExists(): void
    {
        $client = static::createClient();
        $client->request('PUT', 'tasks/42');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testEditWhenStatusNotValid(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $em = $container->get(EntityManagerInterface::class);
        $task = (new Task())
            ->setTitle('Test title')
        ;
        $em->persist($task);
        $em->flush();
        $id = $task->getId();

        $client->request('PUT', "tasks/{$id}", [
            'status' => '333', // fake one
        ]);

        $this->assertResponseStatusCodeSame(400);
    }
}
