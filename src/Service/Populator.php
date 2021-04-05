<?php

namespace App\Service;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Populator
{
    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $em,
        private PokemonRepository $pokemonRepo,
        private Slugger $slugger
    ) {
    }

    public function populate(): void
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
                if ($languageName === 'fr') {
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

            //slug
            $pokemonObject->setSlug($this->slugger->slugIt($pokemonObject->getName()));

            $this->em->persist($pokemonObject);
            $this->em->flush();
        }
    }

    public function populateColorAndImage(): void
    {
        for ($i = 808; $i <= 898; $i++) {
            $pokemonObject = $this->pokemonRepo->findOneBy(['apiId' => $i]);
            
            // Image
            $response = $this->client->request(
                'GET',
                'https://pokeapi.co/api/v2/pokemon/'.$i
            );
            $content = $response->getContent();
            $array=json_decode($content, true);

            $image=$array['sprites']['front_default'];
            $pokemonObject?->setImage($image);

            // Name
            $response = $this->client->request(
                'GET',
                'https://pokeapi.co/api/v2/pokemon-species/'.$i
            );
            $content = $response->getContent();
            $array=json_decode($content, true);

            // Color
            if (isset($array['color'])) {
                $colorUrl = $array['color']['url'];
                $response = $this->client->request(
                    'GET',
                    $colorUrl
                );
                $content = $response->getContent();
                $arrayColor=json_decode($content, true);
                $colorFr = $arrayColor['names'][1]['name'];
                $pokemonObject?->setColor($colorFr);
            }

            $pokemonObject?->$this->em->persist($pokemonObject);
            $this->em->flush();
        }
    }
}
