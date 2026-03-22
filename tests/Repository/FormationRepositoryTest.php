<?php
namespace App\Tests\Repository;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase
{
    public function testFindByContainValue()
    {
        self::bootKernel();
        $repository = static::getContainer()->get(FormationRepository::class);
        
        // Teste la recherche 
        $formations = $repository->findByContainValue('title', 'Java');
        $this->assertIsArray($formations);
    }
}