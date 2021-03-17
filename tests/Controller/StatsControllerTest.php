<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatsControllerTest extends WebTestCase
{
    public function testDashboardShow()
    {
        $client = static::createClient();
        $client->request('GET', '/stats');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Statistiques', $client->getResponse()->getContent());
    }
}
