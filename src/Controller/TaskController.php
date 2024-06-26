<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(private TaskRepository $taskRepo) { }

    #[Route('/tasks', name: 'tasks_list')]
    public function index(): JsonResponse
    {
        $result = [];
        $tasks = $this->taskRepo->findAll();

        foreach ($tasks as $task) {
            $result[] = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'status' => $task->getStatus()->value,
            ];
        }

        return $this->json(['tasks' => $result]);
    }

    #[Route('/tasks/{id}', name: 'single_task')]
    public function single(int $id): JsonResponse
    {
        $task = $this->taskRepo->find($id);
        $result = [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus()->value,
        ];

        return $this->json(['taskData' => $result]);
    }
}
