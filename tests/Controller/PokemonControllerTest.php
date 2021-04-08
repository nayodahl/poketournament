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

    public function testPokemonShow(): void
    {
        $client = static::createClient();
        $client->request('GET', '/pokedex/salameche');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Salamèche', $client->getResponse()->getContent());
        $this->assertStringContainsString('Description de Salamèche', $client->getResponse()->getContent());
        $this->assertStringContainsString('Reptincel', $client->getResponse()->getContent());
        $this->assertStringContainsString('Dracaufeu', $client->getResponse()->getContent());
        $this->assertStringContainsString('Feu', $client->getResponse()->getContent());
    }
}
