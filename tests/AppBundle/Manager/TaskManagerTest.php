<?php

namespace Tests\AppBundle\Manager;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Manager\TaskManager;
use Doctrine\ORM\EntityManagerInterface;
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

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->entity = new TaskManager($this->entityManager);
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
}