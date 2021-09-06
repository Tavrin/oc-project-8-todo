<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskManager
{
    private $em ;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createTask(Task $task, ?UserInterface $user): Task
    {
        $task->setUser($user);
        $this->em->persist($task);
        $this->em->flush();
        return $task;
    }
}