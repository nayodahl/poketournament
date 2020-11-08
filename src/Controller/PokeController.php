<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
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
