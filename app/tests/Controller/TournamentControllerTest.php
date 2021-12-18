<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TournamentControllerTest extends WebTestCase
{
    public function testHomepage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Test Tournament', $client->getResponse()->getContent());
    }

    public function testTournamentCreate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/create');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Choisis un nom pour ton tournoi', $client->getResponse()->getContent());
    }

    public function testTournamentShow(): void
    {
        $client = static::createClient();
        $client->request('GET', '/show');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Test Tournament', $client->getResponse()->getContent());
        $this->assertStringContainsString('Tableau', $client->getResponse()->getContent());
    }
}
