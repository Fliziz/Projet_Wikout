<?php

namespace App\Tests\Controller;

use App\Controller\AccueilController;
use App\Repository\FichesRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class AccueilControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient(); // createClient() crée un client qui peut simuler des requêtes HTTP
        $crawler = $client->request('GET', '/'); // crawler permet de faire des assertions sur le contenu de la page

        $this->assertResponseIsSuccessful();
    }
}