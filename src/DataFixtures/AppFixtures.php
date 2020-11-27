<?php

namespace App\DataFixtures;

use App\Entity\Pokemon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PokePHP\PokeApi;
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
        $api = new PokeApi;

        $response = $this->client->request(
            'GET',
            'https://pokeapi.co/api/v2/pokemon?limit=100'
        );
        $content = $response->getContent();
        $array=json_decode($content, true);
        $arrayResult=$array['results'];

        foreach ($arrayResult as $key) {
            $pokemonObject = new Pokemon();

            $pokemonSpecies = $api->pokemonSpecies($key['name']);
            $array = json_decode($pokemonSpecies, true);

            $colorName = $array['color']['name'];
            $pokemonColor = $api->pokemonColor($colorName);
            $arrayColor = json_decode($pokemonColor, true);
            $colorFr = $arrayColor['names'][1]['name'];
            $pokemonObject->setColor($colorFr);
            
            $nameFr = $array['names'][4]['name'];
            $pokemonObject->setName($nameFr);

            $pokemon = $api->pokemon($key['name']);
            $array = json_decode($pokemon, true);
            $apiId = $array['id'];
            $pokemonObject->setApiId($apiId);
            $image=$array['sprites']['front_default'];
            $pokemonObject->setImage($image);

            $manager->persist($pokemonObject);
            $manager->flush();
        }
    }
}
