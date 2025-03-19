<?php

/*
 * Classe de test pour le contrôleur Playlist.
 * Vérifie le tri, le filtrage et l'affichage des playlists.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe de test pour le contrôleur Playlist.
 */
class PlaylistControllerTest extends WebTestCase
{
    /**
     * URL de la page des playlists.
     */
    const URLPLAYLIST = '/playlists';
    
    /**
     * CSS pour Sélectionner la première cellule du tableau des playlists.
     */
    const TABLEAULISTEPLAYLIST = 'table tbody tr:first-child td';

    /**
     * Teste le tri des playlists par nom en ordre ascendant.
     */
    public function testTriPlaylistPlASC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URLPLAYLIST);
        
        $link = $crawler->filter('a[href$="/playlists/tri/name/ASC"]')->link();
        $client->click($link);
        
        $this-> assertResponseStatusCodeSame(Response::HTTP_OK);
        $this-> assertSelectorTextContains(
            self::TABLEAULISTEPLAYLIST,
            'Bases de la programmation (C#)'
        );
    }
    
    /**
     * Teste le tri des playlists par nom en ordre descendant.
     */
    public function testTriPlaylistPlDESC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URLPLAYLIST);
        
        $link = $crawler->filter('a[href$="/playlists/tri/name/DESC"]')->link();
        $client->click($link);
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains(
            self::TABLEAULISTEPLAYLIST,
            'Visual Studio 2019 et C#'
        );
    }
    
    /**
     * Teste le tri des playlists par nombre de formations en ordre ascendant.
     */
    public function testTriFormationNbASC()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLPLAYLIST);
        
        $link = $crawler->filter('a[href$="playlists/tri/formations/ASC"]')->link();
        $client->click($link);
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains(
            self::TABLEAULISTEPLAYLIST,
            'playlist test'
        );
    }
    
    /**
     * Teste le tri des playlists par nombre de formations en ordre descendant.
     */
    public function testTriFormationNbDESC()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLPLAYLIST);
        
        $link = $crawler->filter('a[href$="playlists/tri/formations/DESC"]')->link();
        $client->click($link);
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains(
            self::TABLEAULISTEPLAYLIST,
            'Bases de la programmation (C#)'
        );
    }

    /**
     * Teste le filtrage des playlists par nom.
     */
    public function testPlaylistPlFilter()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLPLAYLIST);

        $form = $crawler->filter('#pl-playlist-filtre')->form();
        $form['recherche'] = 'sujet';

        $crawler = $client->submit($form);

        $this->assertResponseIsSuccessful();

        $rows = $crawler->filter('table tbody tr');
        $this->assertCount(8, $rows);

        $this->assertSelectorTextContains(
            self::TABLEAULISTEPLAYLIST,
            'Exercices objet (sujets EDC BTS SIO)'
        );
    }

    /**
     * Teste le filtrage des playlists par catégorie.
     */
    public function testCategoriePlFilter()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLPLAYLIST);

        $form = $crawler->filter('#pl-categorie-filtre')->form();
        $form['recherche']->select('4');

        $crawler = $client->submit($form);

        $this->assertResponseIsSuccessful();

        $rows = $crawler->filter('table tbody tr');
        $this->assertCount(1, $rows);

        $this->assertSelectorTextContains(
            self::TABLEAULISTEPLAYLIST,
            'Programmation sous Python'
        );
    }
    
    /**
     * Teste le clic sur une playlist spécifique.
     */
    public function testPlaylistButton()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLPLAYLIST);
        
        $link = $crawler->filter('a[href$="playlists/playlist/23"]')->link();
        $client->click($link);
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Cours Triggers');
    }
}
