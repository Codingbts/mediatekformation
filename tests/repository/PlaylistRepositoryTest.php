<?php

/*
 * Classe de test pour le repository Playlist.
 * Vérifie les fonctionnalités liées à la gestion des playlists.
 */

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Classe de test pour PlaylistRepository.
 */
class PlaylistRepositoryTest extends KernelTestCase
{
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
     * Teste la méthode findAllOrderByFormationNb avec l'ordre ASC.
     * Vérifie que les playlists sont triées par nombre de formations (croissant).
     */
    public function testFindAllOrderByFormationNbASC()
    {
        $playlistRepository = $this->recupPlaylistRepository();
        $nbPlaylist = $playlistRepository->count([]);

        $this->assertEquals(28, $nbPlaylist, 'Le nombre total de playlists devrait être 28.');

        $resultAsc = $playlistRepository->findAllOrderByFormationNb('ASC');
        $firstPlaylist = $resultAsc[0];

        $this->assertSame(
            'playlist test',
            $firstPlaylist->getName(),
            'Le nom de la première playlist ne correspond pas.'
        );
    }

    /**
     * Teste la méthode findAllOrderByFormationNb avec l'ordre DESC.
     * Vérifie que les playlists sont triées par nombre de formations (décroissant).
     */
    public function testFindAllOrderByFormationNbDESC()
    {
        $playlistRepository = $this->recupPlaylistRepository();
        $nbPlaylist = $playlistRepository->count([]);

        $this->assertEquals(28, $nbPlaylist, 'Le nombre total de playlists devrait être 28.');

        $resultDesc = $playlistRepository->findAllOrderByFormationNb('DESC');
        $firstPlaylist = $resultDesc[0];

        $this->assertSame(
            'Bases de la programmation (C#)',
            $firstPlaylist->getName(),
            'Le nom de la première playlist ne correspond pas.'
        );
    }
}
