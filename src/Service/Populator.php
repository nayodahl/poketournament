<?php

namespace App\Service;

use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use PokePHP\PokeApi;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Populator
{

    private $client;
    private $em;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }

    public function populate()
    {
        $api = new PokeApi;

        $response = $this->client->request(
            'GET',
            'https://pokeapi.co/api/v2/pokemon?limit=10'
        );
        $content = $response->getContent();
        $array=json_decode($content, true);
        $arrayResult=$array['results'];

        foreach ($arrayResult as $key) {
            $pokemonObject = new Pokemon();

            $pokemon = $api->pokemonSpecies($key['name']);
            $array = json_decode($pokemon, true);
            $colorName = $array['color']['name'];
            $color = $api->pokemonColor($colorName);
            $arrayColor = json_decode($color, true);
            $colorFr = $arrayColor['names'][1]['name'];
            $nameFr = $array['names'][4]['name'];

            $pokemonObject->setName($nameFr);
            $pokemonObject->setColor($colorFr);

            $entityManager = $this->em;
            $entityManager->persist($pokemonObject);
            $entityManager->flush();
        }
    }
}
