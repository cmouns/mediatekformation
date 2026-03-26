<?php
namespace App\Tests\Entity;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase
{
    public function testGetPublishedAtString()
    {
        $formation = new Formation();
        // Fixe une date précise pour le test
        $formation->setPublishedAt(new \DateTime("2026-03-22"));
        
        // Vérifie que ta méthode renvoie bien le bon format string
        $this->assertEquals("22/03/2026", $formation->getPublishedAtString());
    }

    public function testGetPublishedAtStringNull()
    {
        $formation = new Formation();
        // On ne set aucune date, donc publishedAt = null
        
        // Vérifie que la méthode renvoie bien une chaîne vide
        $this->assertEquals("", $formation->getPublishedAtString());
    }
}