<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PokemonControllerTest extends WebTestCase
{
    public function testPokedexShow(): void
    {
        $client = static::createClient();
        $client->request('GET', '/pokedex');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Pokédex', $client->getResponse()->getContent());
    }
}
