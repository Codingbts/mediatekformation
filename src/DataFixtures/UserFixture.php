<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private $passwordHash;
    
    public function __construct(UserPasswordHasherInterface $passwordHash) {
        $this->passwordHash = $passwordHash;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("admin");
        $plaintextPassword = "admin";
        $passwordHash = $this->passwordHash->hashPassword(
                $user, 
                $plaintextPassword
        );
        $user->setPassword($passwordHash);
        $user->setRoles (['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }
}
