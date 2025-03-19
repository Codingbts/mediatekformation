<?php

/**
 * Classe de test pour le repository Categorie.
 * Vérifie les fonctionnalités liées à la gestion des catégories.
 */

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe de test pour CategorieRepository.
 */
class CategorieRepositoryTest extends KernelTestCase
{
    /**
     * Récupère une instance de CategorieRepository.
     *
     * @return CategorieRepository
     */
    public function recupCategorieRepository(): CategorieRepository
    {
        self::bootKernel();
        return self::getContainer()->get(CategorieRepository::class);
    }

    /**
     * Génère un objet Categorie avec des valeurs.
     *
     * @return Categorie
     */
    public function newCategorie(): Categorie
    {
        return (new Categorie())
            ->setName('Test');
    }

    /**
     * Teste la méthode findOneByName de CategorieRepository.
     * Vérifie qu'une catégorie peut être trouvée par son nom.
     */
    public function testFindOneByName()
    {
        $categorieRepository = $this->recupCategorieRepository();

        $categorie = $this->newCategorie();
        $categorie->setName('SQL');
        $categorieRepository->add($categorie);

        $result = $categorieRepository->findOneByName('SQL');

        $this->assertEquals('SQL', $result[0]->getName());
    }
}
