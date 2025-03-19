<?php

/*
 * Classe de test pour valider les dates de publication des formations.
 * Vérifie que les dates de publication des formations sont correctement validées.
 */

namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Playlist;

/**
 * Classe de test pour les validations des dates de publication des formations.
 */
class FormationDatePostTest extends KernelTestCase
{

    /**
     * Crée et retourne une instance de Formation avec des valeurs par défaut.
     *
     * @return Formation
     */
    public function getFormation()
    {
        $playlist = new Playlist();
        $playlist->setName("Cours");
        
        return (new Formation())
        ->setTitle("Python")
        ->setVideoId("hWtHkP9uwR8")
        ->setPlaylist($playlist);
    }

    /**
     * Assertion personnalisée pour vérifier le nombre d'erreurs de validation.
     *
     * @param Formation $formation L'objet Formation à valider.
     * @param int $nbErreur Le nombre d'erreurs attendu.
     */
    public function assertErrors(Formation $formation, int $nbErreur)
    {
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreur, $error);
    }

    /**
     * Teste la validation d'une date de publication valide.
     */
    public function testValidateDate()
    {
        $formation = $this->getFormation()->setPublishedAt(new \DateTime("2025-02-25"));
        $this->assertErrors($formation, 0);
    }
    
    /**
     * Teste la validation d'une date de publication invalide.
     */
    public function testNonValidateDate()
    {
        $formation = $this->getFormation()->setPublishedAt(new \DateTime("2025-06-25"));
        $this->assertErrors($formation, 1);
    }
}
