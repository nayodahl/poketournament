<?php

namespace App\Controller;

use App\Form\TournamentType;
use App\Repository\TournamentRepository;
use App\Service\Initializor;
use App\Service\Populator;
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
        //sleep(5);
        
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
    public function tournamentView(): Response
    {
        return $this->render('poke/tournament.html.twig', [
            'controller_name' => 'PokeController',
        ]);
    }
}
