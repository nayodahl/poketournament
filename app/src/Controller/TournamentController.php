<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Form\TournamentType;
use App\Repository\TournamentRepository;
use App\Service\Initializor;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use MongoDB\Driver\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournamentController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(TournamentRepository $tournamentRepo): Response
    {
        $response = $this->render('tournament/homepage.html.twig', [
            'tournament' => $tournamentRepo->findLatest(),
        ]);
        
        return $response;
    }
    
    /**
     * @Route("/show", name="app_view")
     */
    public function tournamentShow(TournamentRepository $tournamentRepo, Initializor $initializor): Response
    {
        $latest = $tournamentRepo->findLatest();

        $initializor->updateBracket($latest);

        return $this->render('tournament/show.html.twig', [
            'tournament' => $latest
        ]);
    }

    /**
     * @Route("/create", name="app_create")
     */
    public function tournamentCreate(Request $request, Initializor $initializor, ManagerRegistry $doctrine): Response
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

            $em = $doctrine->getManagerForClass(Tournament::class);
            $em->persist($tournament);
            $em->flush();

            $this->addFlash('success', 'Tournoi sauvegardé');
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('tournament/create.html.twig', [
            'tournamentForm' => $form->createView(),
        ]);
    }
}
