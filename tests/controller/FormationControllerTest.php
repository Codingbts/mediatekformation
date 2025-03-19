<?php

/*
 * Classe de test pour le contrôleur Formation.
 * Vérifie le tri, le filtrage et l'affichage des formations.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe de test pour le contrôleur Formation.
 */
class FormationControllerTest extends WebTestCase
{
    /**
     * URL de la page des formations.
     */
    const URLFORMATIONS = '/formations';

    /**
     * CSS pour Sélectionner la première cellule du tableau des formations.
     */
    const TABLEAULISTE = 'table tbody tr:first-child td';

    /**
     * Titre attendu pour la formation Eclipse.
     */
    const TITLEECLIPSE = 'Eclipse n°8 : Déploiement';

    /**
     * Sélecteur CSS pour les lignes du tableau des formations.
     */
    const TABLEFILTER = 'table tbody tr';

    /**
     * Teste le tri des formations par titre en ordre ascendant.
     */
    public function testTriFormationASC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URLFORMATIONS);

        $link = $crawler->filter('a[href$="/formations/tri/title/ASC"]')->link();
        $client->click($link);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains(
            self::TABLEAULISTE,
            'Android Studio (complément n°1) : Navigation Drawer et Fragment'
        );
    }

    /**
     * Teste le tri des formations par titre en ordre descendant.
     */
    public function testTriFormationDESC()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::URLFORMATIONS);

        $link = $crawler->filter('a[href$="/formations/tri/title/DESC"]')->link();
        $client->click($link);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains(
            self::TABLEAULISTE,
            'UML : Diagramme de paquetages'
        );
    }

    /**
     * Teste le tri des formations par nom de playlist en ordre ascendant.
     */
    public function testTriPlaylistASC()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLFORMATIONS);

        $link = $crawler->filter('a[href$="formations/tri/name/ASC/playlist"]')->link();
        $client->click($link);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains(
            self::TABLEAULISTE,
            'Bases de la programmation n°74 - POO : collections'
        );
    }

    /**
     * Teste le tri des formations par nom de playlist en ordre descendant.
     */
    public function testTriPlaylistDESC()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLFORMATIONS);

        $link = $crawler->filter('a[href$="formations/tri/name/DESC/playlist"]')->link();
        $client->click($link);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains(
            self::TABLEAULISTE,
            'C# : ListBox en couleurtes'
        );
    }

    /**
     * Teste le tri des formations par date de publication en ordre ascendant.
     */
    public function testTriDateASC()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLFORMATIONS);

        $link = $crawler->filter('a[href$="formations/tri/publishedAt/ASC"]')->link();
        $client->click($link);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains(
            self::TABLEAULISTE,
            'Cours UML (1 à 7 / 33) : introduction et cas d\'utilisation'
        );
    }

    /**
     * Teste le tri des formations par date de publication en ordre descendant.
     */
    public function testTriDateDESC()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLFORMATIONS);

        $link = $crawler->filter('a[href$="formations/tri/publishedAt/DESC"]')->link();
        $client->click($link);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains(
            self::TABLEAULISTE,
            self::TITLEECLIPSE
        );
    }

    /**
     * Teste le filtrage des formations par titre.
     */
    public function testFormationFilter()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLFORMATIONS);

        $form = $crawler->filter('#formation-filtre')->form();
        $form['recherche'] = 'eclipse';

        $crawler = $client->submit($form);

        $this->assertResponseIsSuccessful();

        $rows = $crawler->filter('table tbody tr');
        $this->assertCount(9, $rows);

        $this->assertSelectorTextContains(
            self::TABLEAULISTE,
            self::TITLEECLIPSE
        );
    }

    /**
     * Teste le filtrage des formations par playlist.
     */
    public function testPlaylistFilter()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLFORMATIONS);

        $form = $crawler->filter('#playlist-filtre')->form();
        $form['recherche'] = 'sql';

        $crawler = $client->submit($form);

        $this->assertResponseIsSuccessful();

        $rows = $crawler->filter(self::TABLEFILTER);
        $this->assertCount(4, $rows);

        $this->assertSelectorTextContains(
            self::TABLEAULISTE,
            'Exercice triggers, sql et correctifs
            (correction sql sujet EDC cas aeroplan 2014 BTS SIO)'
        );
    }

    /**
     * Teste le filtrage des formations par catégorie.
     */
    public function testCategorieFilter()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLFORMATIONS);

        $form = $crawler->filter('#categorie-filtre')->form();
        $form['recherche']->select('2');

        $crawler = $client->submit($form);

        $this->assertResponseIsSuccessful();

        $rows = $crawler->filter(self::TABLEFILTER);
        $this->assertCount(11, $rows);

        $this->assertSelectorTextContains(
            self::TABLEAULISTE,
            'Eclipse n°2 : rétroconception avec ObjectAid'
        );
    }
    
    /**
     * Teste le clic sur une formation spécifique.
     */
    public function testFormationClick()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', self::URLFORMATIONS);
        
        $link = $crawler->filter('a[href$="formations/formation/1"]')->link();
        $client->click($link);
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Eclipse n°8 : Déploiement');
    }
}
