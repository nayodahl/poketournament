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

    public function initTournament(Tournament $tournament)
    {        
        for ($i = 1; $i <= 8; $i++){
            $game = new Game();
            $game->setNumber($i);

            $tournament->addGame($game);

            $this->em->persist($game);
            $this->em->flush();            
        }
    }
}
