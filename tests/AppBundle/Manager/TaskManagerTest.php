<?php

namespace Tests\AppBundle\Manager;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Manager\TaskManager;
use AppBundle\Repository\TaskRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskManagerTest extends TestCase
{
    /**
     * @var TaskManager
     */
    private $entity;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->taskRepository = $this->createMock(TaskRepository::class);
        $this->userRepository = $this->createMock(UserRepository::class);

        $this->entity = new TaskManager($this->entityManager, $this->taskRepository, $this->userRepository);
    }

    public function testCreateTask()
    {
        $content = new Task();
        $content->setTitle('toto');
        $content->setContent('aa');
        $content->setCreatedAt(new \DateTime());

        $user = new User();
        $user->setUsername('user');
        $user->setEmail('email@email.com');
        $user->setPassword('root');
        $expected = clone($content);

        $actual = $this->entity->createTask($content, $user);

        $expected->setUser($user);
        $this->assertEquals($expected, $actual);
    }

    public function testGetTasks()
    {
        $content[0] = new Task();
        $content[0]->setTitle('toto');
        $content[0]->setContent('aa');

        $userContent = new User();
        $userContent->setUsername('anonyme');

        $this->taskRepository->expects($this->any())
            ->method('findAll')
            ->willReturn($content);

        $this->userRepository->method('findOneBy')
            ->willReturn($userContent);

        $expected[0] = clone($content[0]);
        $expected[0]->setUser($userContent);

        $actual = $this->entity->getTasks();

        $this->assertEquals($expected, $actual);
    }
}