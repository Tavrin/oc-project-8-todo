<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $em;
    private $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function manageUser(User $user, bool $isAdmin, bool $isNewUser = true): User
    {
        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        if (true === $isAdmin && false === $user->hasRole('ROLE_ADMIN')) {
            $user->setRoles(['ROLE_ADMIN']);
        } elseif (false === $isAdmin && true === $user->hasRole('ROLE_ADMIN')) {
            $user->setRoles([]);
        }

        if (true === $isNewUser) {
            $this->em->persist($user);
        }

        $this->em->flush();

        return $user;
    }
}