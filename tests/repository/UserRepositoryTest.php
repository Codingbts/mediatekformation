<?php

/*
 * Classe de test pour le repository User.
 * Vérifie les fonctionnalités liées à la gestion des utilisateurs.
 */

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe de test pour UserRepository.
 */
class UserRepositoryTest extends KernelTestCase
{
    /**
     * Récupère une instance de UserRepository.
     *
     * @return UserRepository
     */
    public function recupUserRepository(): UserRepository
    {
        self::bootKernel();
        return self::getContainer()->get(UserRepository::class);
    }

    /**
     * Génère un objet User avec des valeurs.
     *
     * @return User
     */
    public function newUser(): User
    {
        return (new User())
            ->setUsername('Imp')
            ->setPassword('password');
    }

    /**
     * Teste la méthode upgradePassword de UserRepository.
     * Vérifie que le mot de passe d'un utilisateur est correctement mis à jour.
     */
    public function testUpgradePassword()
    {
        $userRepository = $this->recupUserRepository();

        $user = $this->newUser();
        $userRepository->add($user);

        $newHashedPassword = 'new_hashed_password';
        $userRepository->upgradePassword($user, $newHashedPassword);

        $updatedUser = $userRepository->find($user->getId());

        $this->assertEquals($newHashedPassword, $updatedUser->getPassword());
    }
}
