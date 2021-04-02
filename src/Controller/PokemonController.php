<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use App\Service\Populator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PokemonController extends AbstractController
{   
    #[Route('/pokemon', name: 'pokemon')]
    public function index(): Response
    {
        return $this->render('pokemon/index.html.twig', [
            'controller_name' => 'PokemonController',
        ]);
    }

    /**
     * @Route("/pokedex", name="app_pokedex")
     */
    public function pokedexShow(PokemonRepository $pokemonRepo): Response
    {
        return $this->render('pokemon/pokedex.html.twig', [
            'pokemons' => $pokemonRepo->findAll(),
        ]);
    }

    /**
     * @Route("/pokedex/{slug}", name="app_pokemon")
     */
    public function pokemonShow(PokemonRepository $pokemonRepo, string $slug): Response
    {       
        $pokemon = $pokemonRepo->findOneBy([ 'apiId' => 1000 ]);
        
        dump($pokemon);

        $repo = $pokemonRepo;

        dump($repo->childCount($pokemon));
        dump( $repo->childCount($pokemon, true));

        $child = $repo->getChildren($pokemon, true, null, 'ASC', false);
        dump( $child);

        $leaf = $repo->getLeafs($pokemon->getRoot());
        dd($leaf);

        return $this->render('pokemon/pokemon.html.twig', [
            'pokemon' => $pokemonRepo->findOneBy([ 'slug' => $slug ]),
        ]);
    }

    /**
     * @Route("/utility/pokemons", methods="GET", name="app_utility_pokemons")
     */
    public function findPokemonsApi(PokemonRepository $pokemonRepo, Request $request): Response
    {
        $list = $pokemonRepo->findAllAlphabeticalMatching($request->query->get('query'));
       
        return $this->json(['pokemons' => $list], 200, [], ['groups' => ['list_pokemon']]);
    }

    /**
     * @Route("/utility/pokedex", methods="GET", name="app_utility_pokedex")
     */
    public function findAllPokemonsApi(PokemonRepository $pokemonRepo): Response
    {
        $list = $pokemonRepo->findAll();
   
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($list, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['tournaments']]);
        $response = new Response($json, 200, ['Content-Type' => 'application/json']);

        return $response;
    }

    /**
     * @Route("/populate", name="app_populate")
     */
    /*
    public function loadDataFromPokeapi(Populator $populator): Response
    {
        $populator->populateSlug();

        return $this->redirectToRoute('app_homepage');
    }
    */
}
