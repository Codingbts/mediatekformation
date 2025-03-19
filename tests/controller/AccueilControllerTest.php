<?php

/*
 * Classe de test pour le contrôleur Accueil.
 * Vérifie que les pages d'accueil et les liens fonctionnent correctement.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe de test pour le contrôleur Accueil.
 */
class AccueilControllerTest extends WebTestCase
{

    /**
     * Teste l'accès à la page d'accueil.
     */
    public function testAccessPage()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertAnySelectorTextContains('h3', 'Bienvenue sur le site de
               MediaTek86 consacré aux formations en ligne');
    }
    
    /**
     * Teste le clic sur une miniature de formation.
     */
    public function testMiniaClick()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/');
        
        $link = $crawler->filter('a[href$="formations/formation/1"]')->link();
        $client->click($link);
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Eclipse n°8 : Déploiement');
    }
    
    /**
     * Teste le lien vers la page des formations depuis l'accueil.
     */
    public function testLiensFormationAccueil()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/');
        
        $link = $crawler->filter('a[href$="formations"]')->link();
        $client->click($link);
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('table thead tr th', 'formation');
    }
    
    /**
     * Teste le lien vers la page des playlists depuis l'accueil.
     */
    public function testLiensPlaylistAccueil()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/');
        
        $link = $crawler->filter('a[href$="playlists"]')->link();
        $client->click($link);
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('table thead tr th', 'playlist');
    }
}
