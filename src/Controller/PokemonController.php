<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/utility/pokemons", methods="GET", name="app_utility_pokemons")
     */
    public function getPokemonsApi(PokemonRepository $pokemonRepo, Request $request): Response
    {
        $list = $pokemonRepo->findAllAlphabeticalMatching($request->query->get('query'));
        
        return $this->json(['pokemons' => $list], 200, [], ['groups' => ['list_pokemon']]);
    }

    /**
     * @Route("/populate", name="app_populate")
     */
    /*
    public function loadDataFromPokeapi(Populator $populator): Response
    {
        $populator->populateColorAndImage();

        return $this->redirectToRoute('app_homepage');
    }
    */
}
