<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NavigationTest extends WebTestCase
{
    public function testHomePageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful(); // Code 200
    }

    public function testFormationPageSorting()
    {
        $client = static::createClient();
        // Teste l'accès à la page des formations
        $client->request('GET', '/formations');
        $this->assertResponseIsSuccessful();
        // Vérifie si un élément spécifique du tableau est présent
        $this->assertSelectorTextContains('th', 'formation');
    }
}