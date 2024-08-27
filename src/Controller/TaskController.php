<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(
        private TaskRepository $taskRepo,
        private EntityManagerInterface $em
    ) { }

    #[Route('/tasks', methods: ['GET'], name: 'tasks_list')]
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

    #[Route('/tasks', methods: ['POST'], name: 'new_task')]
    public function create(Request $request): JsonResponse
    {
        $payload = $request->getPayload();

        $title = $payload->get('title');
        if (empty($title)) {
            return $this->json(['msg' => 'Title field required'], Response::HTTP_BAD_REQUEST);
        }
        $task = new Task();
        $task->setTitle($title);

        $description = $payload->get('description');
        if (!empty($description)) {
            $task->setDescription($description);
        }

        // Default status is ToDo
        $task->setStatus(TaskStatus::ToDo);


        $this->em->persist($task);
        $this->em->flush();

        return $this->json(['id' => '']);
    }

    #[Route('/tasks/{id}', methods: ['GET'], name: 'single_task')]
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

    #[Route('/tasks/{id}', methods: ['PUT'], name: 'task_edit')]
    public function edit(int $id, Request $request): JsonResponse
    {
        $task = $this->taskRepo->find($id);
        if (is_null($task)) {
            return $this->json(['msg' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $statusVal = $request->get('status');
        if (!is_null($statusVal)) {
            try {
                $status = TaskStatus::from($statusVal);
            } catch (\ValueError $e) {
                return $this->json(['msg' => 'Status is not valid'], Response::HTTP_BAD_REQUEST);
            }

            $task->setStatus($status);
        }

        $title = $request->get('title');
        if (!empty($title)) {
            $task->setTitle($title);
        }

        $description = $request->get('description');
        if (!empty($description)) {
            $task->setDescription($description);
        }

        $this->em->flush();

        return $this->json([], Response::HTTP_OK);
    }
}
