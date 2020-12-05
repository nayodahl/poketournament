<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Pokemon;
use App\Entity\Tournament;
use App\Repository\GameRepository;
use App\Repository\PokemonRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class Initializor
{
    private $em;
    private $pokemonRepo;
    private $gameRepo;

    public function __construct(EntityManagerInterface $em, PokemonRepository $pokemonRepo, GameRepository $gameRepo)
    {
        $this->em = $em;
        $this->pokemonRepo = $pokemonRepo;
        $this->gameRepo = $gameRepo;
    }

    // init all 8 games, but assign players to only 4 first games
    public function initTournament(Tournament $tournament)
    {
        $players = $tournament->getPokemons();

        $j=0;
        for ($i = 1; $i <= 8; $i++) {
            $game = new Game();
            $game->setNumber($i);
            
            if ($i<=4) {
                $game->setPlayer1($players->get($j));
                $j++;
                $game->setPlayer2($players->get($j));
                $j++;
            }

            $tournament->addGame($game);

            $this->em->persist($game);
            $this->em->flush();
        }
    }

    public function setSemi(Tournament $tournament)
    {
        $games = $tournament->getGames();

        foreach ($games as $key) {
            $number=$key->getNumber();

            switch ($number) {
                case '1':
                    if ($key->getWinner() !== null) {
                        $winner1IsSet=true;
                        $winner1=$key->getWinner();
                    }
    
                    break;
    
                case '2':
                    if ($key->getWinner() !== null) {
                        $winner2IsSet=true;
                        $winner2=$key->getWinner();
                    }
    
                    break;

                case '3':
                    if ($key->getWinner() !== null) {
                        $winner3IsSet=true;
                        $winner3=$key->getWinner();
                    }
    
                    break;

                case '4':
                    if ($key->getWinner() !== null) {
                        $winner4IsSet=true;
                        $winner4=$key->getWinner();
                    }
    
                    break;
            }
        }

        if ($winner1IsSet && $winner2IsSet) {
            $game5=$this->gameRepo->findOneByNumberAndTournament(5, $tournament->getId());
            $game5->setPlayer1($winner1);
            $game5->setPlayer2($winner2);
            $game5->setUpdatedAt(new DateTime());

            $this->em->persist($game5);
        }

        if ($winner3IsSet && $winner4IsSet) {
            $game6=$this->gameRepo->findOneByNumberAndTournament(6, $tournament->getId());
            $game6->setPlayer1($winner3);
            $game6->setPlayer2($winner4);
            $game6->setUpdatedAt(new DateTime());

            $this->em->persist($game6);
        }

        $this->em->flush();
    }
}
