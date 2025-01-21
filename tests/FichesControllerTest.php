<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FichesControllerTest extends WebTestCase
{
    public function testindex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/utilisateurs/');

        $this->assertResponseIsSuccessful();
    }
}
