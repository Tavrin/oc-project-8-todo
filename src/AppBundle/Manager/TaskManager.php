<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Repository\TaskRepository;
use AppBundle\Repository\UserRepository;
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
        $tasks = $this->taskRepository->findAll();
        $anonymousUser = $this->userRepository->findOneBy(['username' => 'anonyme']);

        foreach ($tasks as $task) {
            if (null === $task->getUser() && !empty($anonymousUser)) {
                $task->setUser($anonymousUser);
            }
        }

        $this->em->flush();

        return $tasks;
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