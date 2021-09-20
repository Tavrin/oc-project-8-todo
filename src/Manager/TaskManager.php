<?php

namespace App\Manager;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskManager
{
    private const ANONYMOUS_USER = 'anonyme';
    private $em ;
    private $taskRepository;
    private $userRepository;

    public function __construct(EntityManagerInterface $em, TaskRepository $taskRepository, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    public function createTask(Task $task, ?UserInterface $user): Task
    {
        $task->setUser($user);
        $this->em->persist($task);
        $this->em->flush();
        return $task;
    }

    public function getTasks(): array
    {
        return $this->taskRepository->findAll();
    }

    public function deleteTask(Task $task, $user): bool
    {
        if ((self::ANONYMOUS_USER === $task->getUser()->getUsername() && false === $user->hasRole('ROLE_ADMIN')) || (self::ANONYMOUS_USER !== $task->getUser()->getUsername() && $user !== $task->getUser())) {
            return false;
        }

        $this->em->remove($task);
        $this->em->flush();
        return true;
    }
}