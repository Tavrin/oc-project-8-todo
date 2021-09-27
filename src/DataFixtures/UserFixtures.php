<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Id\IdentityGenerator;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements ORMFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('anonyme');
        $user->setId(1);
        $user->setEmail('anonyme@email.com');
        $user->setPassword($this->encoder->encodePassword($user, 'root'));
        $manager->persist($user);
        $metadata = $manager->getClassMetaData(get_class($user));
        $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_NONE);
        $metadata->setIdGenerator(new AssignedGenerator());
        $this->addReference('anonyme', $user);

        $manager->flush();

        $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);
        $metadata->setIdGenerator(new IdentityGenerator());

        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@email.com');
        $user->setPassword($this->encoder->encodePassword($user, 'root'));
        $manager->persist($user);
        $this->addReference('user', $user);

        $user = new User();
        $user->setUsername('user2');
        $user->setEmail('user2@email.com');
        $user->setPassword($this->encoder->encodePassword($user, 'root'));
        $manager->persist($user);
        $this->addReference('user2', $user);

        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@email.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->encoder->encodePassword($user, 'root'));
        $manager->persist($user);
        $this->addReference('admin', $user);

        $manager->flush();
    }
}
