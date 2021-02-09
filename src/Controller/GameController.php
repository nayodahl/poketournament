<?php

namespace App\Controller;

use App\Form\GameType;
use App\Repository\GameRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
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

        return $this->render('game/edit.html.twig', [
            'gameForm' => $form->createView(),
            'game' => $game,
        ]);
    }
}
