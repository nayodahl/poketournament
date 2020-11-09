<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PokePHP\PokeApi;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Entity\Pokemon;

class PokeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);  

        $api = new PokeApi;
        $pikachu = $api->pokemonSpecies('pikachu');
        $person = $serializer->deserialize($pikachu, Pokemon::class, 'json');
        $color = $person->getColor(['name']);
        
        return $this->render('poke/index.html.twig', [
            'controller_name' => 'PokeController',
        ]);
    }

    /**
     * @Route("/tournoi", name="tournament")
     */
    public function tournamentView(): Response
    {
        return $this->render('poke/tournament.html.twig', [
            'controller_name' => 'PokeController',
        ]);
    }
}
