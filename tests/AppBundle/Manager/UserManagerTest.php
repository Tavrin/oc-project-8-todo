<?php

namespace Tests\AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var UserManager
     */
    private $entity;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->encoder = $this->createMock(UserPasswordEncoderInterface::class);

        $this->entity = new UserManager($this->entityManager, $this->encoder);
    }

    public function testManageUserUnsetAdmin()
    {
        $this->encoder->method('encodePassword')
            ->willReturn('bbb');

        $user = new User();
        $user->setUsername('toto');
        $user->setPassword('aaa');
        $user->setRoles(['ROLE_ADMIN']);

        $expected = clone($user);
        $expected->setRoles([]);
        $expected->setPassword('bbb');

        $actual = $this->entity->manageUser($user, false, false);

        $this->assertEquals($expected, $actual);
    }

    public function testManageUseSetAdmin()
    {
        $this->encoder->method('encodePassword')
            ->willReturn('bbb');

        $user = new User();
        $user->setUsername('toto');
        $user->setPassword('aaa');

        $expected = clone($user);
        $expected->setRoles(['ROLE_ADMIN']);
        $expected->setPassword('bbb');

        $actual = $this->entity->manageUser($user, true, false);

        $this->assertEquals($expected, $actual);
    }
}