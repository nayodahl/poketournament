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
        for ($i = 800; $i <= 800; $i++) {
            $pokemonObject = new Pokemon();
            $pokemonObject->setApiId($i);
            
            // Image
            $response = $this->client->request(
                'GET',
                'https://pokeapi.co/api/v2/pokemon/'.$i
            );
            $content = $response->getContent();
            $array=json_decode($content, true);    

            $image=$array['sprites']['front_default'];
            $pokemonObject->setImage($image);

            // Name
            $response = $this->client->request(
                'GET',
                'https://pokeapi.co/api/v2/pokemon-species/'.$i
            );
            $content = $response->getContent();
            $array=json_decode($content, true);
            $names=$array['names'];
            foreach ($names as $key) {
                $languageName=$key['language']['name'];
                if ($languageName === 'fr'){
                    $namefr=$key['name'];
                    $pokemonObject->setName($namefr);
                }
            }

            // Color
            $colorUrl = $array['color']['url'];
            $response = $this->client->request(
                'GET',
                $colorUrl
            );
            $content = $response->getContent();
            $arrayColor=json_decode($content, true);
            $colorFr = $arrayColor['names'][1]['name'];
            $pokemonObject->setColor($colorFr);

            $this->em->persist($pokemonObject);
            $this->em->flush();
        }
        
        /*
        $response = $this->client->request(
            'GET',
            'https://pokeapi.co/api/v2/pokemon?offset=500&limit=1'
        );
        $content = $response->getContent();
        $array=json_decode($content, true);
        $arrayResult=$array['results'];

        foreach ($arrayResult as $key) {
            $pokemonObject = new Pokemon();

            $pokemonSpecies = $api->pokemonSpecies($key['name']);
            $array = json_decode($pokemonSpecies, true);

            if (isset($array['color']['name'])){
                $colorName = $array['color']['name'];
                $pokemonColor = $api->pokemonColor($colorName);
                $arrayColor = json_decode($pokemonColor, true);
                $colorFr = $arrayColor['names'][1]['name'];
                $pokemonObject->setColor($colorFr); 
            }

            if (isset($array['names'][4]['name'])){
                $nameFr = $array['names'][4]['name'];
                $pokemonObject->setName($nameFr);

                $pokemon = $api->pokemon($key['name']);
                $array = json_decode($pokemon, true);
                $apiId = $array['id'];
                $pokemonObject->setApiId($apiId);
                $image=$array['sprites']['front_default'];
                $pokemonObject->setImage($image);
    
                $this->em->persist($pokemonObject);
                $this->em->flush();
            }
        }
        */
    }
}
