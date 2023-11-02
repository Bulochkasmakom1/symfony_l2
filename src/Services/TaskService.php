<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TaskRepository;

class TaskService
{
    private $taskRepository;
    private $entityManager;

    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $entityManager)
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
    }

    public function listTask(): array
    {
        return $this->taskRepository->findAll();
    }
    public function getHappyMessage(): string
    {
    $messages = [
    'You did it! You updated the system!',
    'That was one of the coolest updates!',
    'Great work! Keep going!',
    ];
    $index = array_rand($messages);
    return $messages[$index];
    }

    public function viewTask(int $id)
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            throw new \Exception("Task{$id} not found");
        }
        return $task;
    }

    public function deleteTask(int $id)
    {
        // Находим задачу по ID
        $task = $this->taskRepository->find($id);

        if (!$task) {
            throw new \Exception("Task{$id} not found");
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $task;
    }

    public function createTask($request, $form)
    {

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $this->entityManager->persist($task);
            $this->entityManager->flush();

        }

        return $form;
    }

    public function editTask($request, $id, $task)
    {
        if (!$task) {
            throw new \Exception("Task{$id} not found");
        }
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }
    

}