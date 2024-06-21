<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(private TaskRepository $taskRepo) { }

    #[Route('/tasks', name: 'list_tasks')]
    public function index(): JsonResponse
    {
        $result = [];
        $tasks = $this->taskRepo->findAll();

        foreach ($tasks as $task) {
            $result[] = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus()->value,
            ];
        }

        return $this->json(['tasks' => $result]);
    }
}
