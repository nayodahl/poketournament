<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use App\Service\Populator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokeController extends AbstractController
{   
    /**
     * @Route("/", name="homepage")
     */
    public function index(PokemonRepository $pokemonRepo, Populator $populator): Response
    {
        $populator->populate();
        
        return $this->render('poke/index.html.twig', [
            'pokemons' => $pokemonRepo->findAll(),
        ]);

        //return $this->render('task/list.html.twig', ['tasks' => $paginated]);
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
