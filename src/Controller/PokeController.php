<?php

namespace App\Controller;

use App\Form\TournamentType;
use App\Repository\TournamentRepository;
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
        return $this->render('poke/index.html.twig', [
            'tournament' => $tournamentRepo->findLatest(),
        ]);

        //return $this->render('task/list.html.twig', ['tasks' => $paginated]);
    }

    /**
     * @Route("/create", name="app_create")
     */
    public function tournamentCreate(Request $request): Response
    {
        $form = $this->createForm(TournamentType::class);

        // handling comment form POST request if any
        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {
            $tournament = $form->getData();

            $tournament->setDate(new DateTime());

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
     * @Route("/tournoi", name="app_view")
     */
    public function TournamentView(): Response
    {
        return $this->render('poke/tournament.html.twig', [
            'controller_name' => 'PokeController',
        ]);
    }
}
