<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Fixture pour charger un utilisateur admin dans la base de données.
 */
class UserFixture extends Fixture
{
    /**
     * Service de hachage de mot de passe.
     * @var UserPasswordHasherInterface
     */
    private $passwordHash;
    
    /**
     * Constructeur.
     * @param UserPasswordHasherInterface $passwordHash
     */
    public function __construct(UserPasswordHasherInterface $passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * Charge un utilisateur admin dans la base de données.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("admin");
        $plaintextPassword = "admin";
        $passwordHash = $this->passwordHash->hashPassword($user, $plaintextPassword);
        $user->setPassword($passwordHash);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }
}
