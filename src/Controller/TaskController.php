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

    #[Route('task/new'), name: 'new_task']
    public function create(Request $request): JsonResponse
    {
        $title = $request->get('title');
        if (empty($title)) {
            return $this->json(['msg' => 'Title field required'], Response::HTTP_BAD_REQUEST);
        }
        $task = new Task();
        $task->setTitle($title);

        $description = $request->get('description');
        if (!empty($description)) {
            $task->setDescription($description);
        }

        // Default status is ToDo
        $task->setStatus(TaskStatus::ToDo);


        $this->em->persist($task);
        $this->em->flush();

        return $this->json(['id' => '']);
    }
}
