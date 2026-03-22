<?php
namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationValidationTest extends KernelTestCase
{
    public function testDatePasPosterieure()
    {
        self::bootKernel();
        $validator = static::getContainer()->get('validator');
        
        $formation = new Formation();
        $formation->setTitle("Test validation date");
        // Met une date à demain (+1 day)
        $formation->setPublishedAt(new \DateTime("+1 day"));
        
        $errors = $validator->validate($formation);
        $this->assertGreaterThan(0, count($errors), "Une date future devrait générer une erreur de validation");
    }
}