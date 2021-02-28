<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Repository\GameRepository;
use App\Repository\PokemonRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class InitializorTest
{
    public function __construct(
        private EntityManagerInterface $em,
        private PokemonRepository $pokemonRepo,
        private GameRepository $gameRepo
    ) {
    }

}
