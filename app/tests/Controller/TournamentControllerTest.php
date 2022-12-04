<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TournamentControllerTest extends WebTestCase
{
    public function testHomepage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Test Tournament', $client->getResponse()->getContent());
    }

    public function testTournamentCreate(): void
    {
        $client = static::createClient();
        $client->request('GET', '/create');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Choisis un nom pour ton tournoi', $client->getResponse()->getContent());
    }

    public function testTournamentShow(): void
    {
        $client = static::createClient();
        $client->request('GET', '/show');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Test Tournament', $client->getResponse()->getContent());
        $this->assertStringContainsString('Tableau', $client->getResponse()->getContent());
    }
}
