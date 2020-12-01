<?php

namespace App\DataFixtures;

use App\Entity\Pokemon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    
    public function load(ObjectManager $manager)
    {

    }
}
