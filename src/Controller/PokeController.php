<?php

namespace App\Controller;

use App\Form\GameType;
use App\Form\TournamentType;
use App\Repository\GameRepository;
use App\Repository\TournamentRepository;
use App\Service\Initializor;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokeController extends AbstractController
{

    /**
     * @Route("/", name="app_homepage")
     */
    public function index(TournamentRepository $tournamentRepo): Response
    {
        $response = $this->render('poke/index.html.twig', [
            'tournament' => $tournamentRepo->findLatest(),
        ]);
        
        return $response;
    }

    /**
     * @Route("/create", name="app_create")
     */
    public function tournamentCreate(Request $request, Initializor $initializor): Response
    {
        $form = $this->createForm(TournamentType::class);

        // handling comment form POST request if any
        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {
            $tournament = $form->getData();
            $tournament->setDate(new DateTime());

            $pokemon = $form['pokemon1']->getData();
            if ($pokemon) {
                $tournament->addPokemon($pokemon);
            }
            $pokemon = $form['pokemon2']->getData();
            if ($pokemon) {
                $tournament->addPokemon($pokemon);
            }
            $pokemon = $form['pokemon3']->getData();
            if ($pokemon) {
                $tournament->addPokemon($pokemon);
            }
            $pokemon = $form['pokemon4']->getData();
            if ($pokemon) {
                $tournament->addPokemon($pokemon);
            }
            $pokemon = $form['pokemon5']->getData();
            if ($pokemon) {
                $tournament->addPokemon($pokemon);
            }
            $pokemon = $form['pokemon6']->getData();
            if ($pokemon) {
                $tournament->addPokemon($pokemon);
            }
            $pokemon = $form['pokemon7']->getData();
            if ($pokemon) {
                $tournament->addPokemon($pokemon);
            }
            $pokemon = $form['pokemon8']->getData();
            if ($pokemon) {
                $tournament->addPokemon($pokemon);
            }

            $initializor->initTournament($tournament);

            $em = $this->getDoctrine()->getManager();
            $em->persist($tournament);
            $em->flush();

            $this->addFlash('success', 'Tournoi sauvegardé');
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('poke/create.html.twig', [
            'tournamentForm' => $form->createView(),
        ]);
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

    /**
     * @Route("/tournoi", name="app_view")
     */
    public function tournamentView(TournamentRepository $tournamentRepo, Initializor $initializor): Response
    {
        $latest = $tournamentRepo->findLatest();

        $initializor->setSemi($latest);

        return $this->render('poke/tournament.html.twig', [
            'tournament' => $latest
        ]);
    }

    /**
     * @Route("/game/{gameId}", name="app_edit_game", requirements={"trickId"="\d+"})
     */
    public function gameEdit(int $gameId, Request $request, GameRepository $gameRepo): Response
    {
        $game = $gameRepo->find($gameId);
        $form = $this->createForm(GameType::class, $game);

        // handling comment form POST request if any
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game = $form->getData();
            $game->setUpdatedAt(new DateTime());
            // set winner and loser
            if ($game->getScorePlayer1() > $game->getScorePlayer2()) {
                $game->setWinner($game->getPlayer1());
                $game->setLoser($game->getPlayer2());
            }
            if ($game->getScorePlayer1() < $game->getScorePlayer2()) {
                $game->setWinner($game->getPlayer2());
                $game->setLoser($game->getPlayer1());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();

            $this->addFlash('success', 'Match sauvegardé');

            return $this->redirectToRoute('app_view');
        }

        return $this->render('poke/game.html.twig', [
            'gameForm' => $form->createView(),
            'game' => $game,
        ]);
    }
}
