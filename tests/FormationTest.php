<?php

namespace App\Tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 *
 * Vérifie le bon fonctionnement de la méthode getPublishedAtString().
 * @author Saad
 */
class FormationTest extends TestCase
{
    
    /**
     * Teste la méthode getPublishedAtString de l'entité Formation.
     * Vérifie que la date de publication est bien formatée.
     */
    public function testGetPublishedAtString()
    {
        
       $formation = new Formation();
       $formation->setPublishedAt(new DateTime("2025-03-18"));
       $this->assertEquals("18/03/2025", $formation->getPublishedAtString());
        
    }
}
