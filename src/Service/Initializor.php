<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Pokemon;
use App\Entity\Tournament;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;

class Initializor
{
    private $em;
    private $pokemonRepo;

    public function __construct(EntityManagerInterface $em, PokemonRepository $pokemonRepo)
    {
        $this->em = $em;
        $this->pokemonRepo = $pokemonRepo;
    }

    // init all 8 games, but assign players to only 4 first games
    public function initTournament(Tournament $tournament)
    {        
        $players = $tournament->getPokemons();

        $j=0;
        for ($i = 1; $i <= 8; $i++){
            $game = new Game();
            $game->setNumber($i);
            
            if ( $i<=4 ){
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
}
