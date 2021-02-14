<?php

namespace App\Service;

use App\Repository\TournamentRepository;

class StatsCalculator
{
    private $tournamentRepository;

    public function __construct(TournamentRepository $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
    }

    public function getMostUsedPokemons(): array
    {
        $tournaments = $this->tournamentRepository->findAll();     

        $data=[];
        // get number of participations for each pokemon
        foreach ($tournaments as $tournament){
            foreach ($tournament->getPokemons() as $pokemon){
                $numberOfParticipation = $this->tournamentRepository->getNumberOfParticipation($pokemon->getId());
                $data[$pokemon->getName()] = $numberOfParticipation;
            }
        }
        // Sort Array (Descending Order), According to Value - arsort()
        arsort($data);
        // get only 10 first results 
        $data = array_slice($data, 0, 10);

        return $data;
    }

    public function getMostWonGamesByPokemon(): array
    {
        $tournaments = $this->tournamentRepository->findAll();     

        $data=[];
        // get number of wons games for each pokemon
        foreach ($tournaments as $tournament){
            foreach ($tournament->getPokemons() as $pokemon){
                $numberOfWonGames = $this->tournamentRepository->getNumberOfWonGames($pokemon->getId());
                $data[$pokemon->getName()] = $numberOfWonGames;
            }
        }
        // Sort Array (Descending Order), According to Value - arsort()
        arsort($data);
        // get only 10 first results 
        $data = array_slice($data, 0, 10);

        return $data;
    }
}