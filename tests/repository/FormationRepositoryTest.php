<?php

/*
 * Classe de test pour le repository Formation.
 * Vérifie les fonctionnalités liées à la gestion des formations.
 */

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe de test pour FormationRepository.
 */
class FormationRepositoryTest extends KernelTestCase
{
    /**
     * Récupère une instance de FormationRepository.
     *
     * @return FormationRepository
     */
    public function recupFormationRepository(): FormationRepository
    {
        self::bootKernel();
        return self::getContainer()->get(FormationRepository::class);
    }

    /**
     * Récupère une instance de PlaylistRepository.
     *
     * @return PlaylistRepository
     */
    public function recupPlaylistRepository(): PlaylistRepository
    {
        self::bootKernel();
        return self::getContainer()->get(PlaylistRepository::class);
    }

    /**
     * Génère un objet Formation avec des valeurs.
     *
     * @return Formation
     */
    public function newFormation(): Formation
    {
        return (new Formation())
            ->setTitle('Python')
            ->setDescription('Apprendre python');
    }

    /**
     * Génère un objet Playlist avec des valeurs.
     *
     * @return Playlist
     */
    public function newPlaylist(): Playlist
    {
        return (new Playlist())
            ->setName('Cours');
    }

    /**
     * Teste la méthode findByPlaylist de FormationRepository.
     * Vérifie que les formations sont correctement récupérées par playlist.
     */
    public function testFindByPlaylist()
    {
        $formationRepository = $this->recupFormationRepository();
        $playlistRepository = $this->recupPlaylistRepository();

        $playlist = $this->newPlaylist();
        $playlistRepository->add($playlist);

        $formation1 = $this->newFormation();
        $formation1->setTitle('Formation 1');
        $formation1->setPlaylist($playlist);
        $formationRepository->add($formation1);

        $formation2 = $this->newFormation();
        $formation2->setTitle('Formation 2');
        $formation2->setPlaylist($playlist);
        $formationRepository->add($formation2);

        $formations = $formationRepository->findByPlaylist($playlist);

        $this->assertCount(2, $formations);
        $this->assertEquals('Formation 1', $formations[0]->getTitle());
        $this->assertEquals('Formation 2', $formations[1]->getTitle());
    }
}
