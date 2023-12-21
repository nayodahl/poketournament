<?php

namespace App\Service;

use App\Repository\GameRepository;
use App\Repository\PokemonRepository;
use App\Repository\TournamentRepository;

class StatsCalculator
{
    public function __construct(
        private readonly GameRepository $gameRepository,
        private readonly PokemonRepository $pokemonRepository,
        private readonly TournamentRepository $tournamentRepository,
    ) {
    }

    /**
     * @return array<string, int> $data
     */
    public function getMostUsedPokemons(): array
    {
        $tournaments = $this->tournamentRepository->findAll();

        $data=[];
        // get number of participations for each pokemon
        foreach ($tournaments as $tournament) {
            foreach ($tournament->getPokemons() as $pokemon) {
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

    /**
     * @return array<string, int> $data
     */
    public function getMostWonGamesByPokemon(): array
    {
        $tournaments = $this->tournamentRepository->findAll();

        $data=[];
        // get number of wons games for each pokemon
        foreach ($tournaments as $tournament) {
            foreach ($tournament->getPokemons() as $pokemon) {
                $numberOfWonGames = $this->gameRepository->getNumberOfWonGames($pokemon->getId());
                $data[$pokemon->getName()] = $numberOfWonGames;
            }
        }
        // Sort Array (Descending Order), According to Value - arsort()
        arsort($data);
        // get only 10 first results
        $data = array_slice($data, 0, 10);

        return $data;
    }

    /**
     * @return array<string, int> $data
     */
    public function getPointsByTournament(): array
    {
        $tournaments = $this->tournamentRepository->findAll();

        $data=[];
        foreach ($tournaments as $tournament) {
            $numberOfPoints = 0;
            foreach ($tournament->getGames() as $game) {
                $numberOfPoints = $numberOfPoints + $game->getScorePlayer1() + $game->getScorePlayer2();
            }
            $data[$tournament->getName()] = $numberOfPoints;
        }

        return $data;
    }

    /**
     * @return array<int|string, int> $data
     */
    public function getPokemonByColor(): array
    {
        $result = $this->pokemonRepository->getAllDistinctColors();
        $colors = array_column($result, 'color');

        $data=[];
        foreach ($colors as $color) {
            $quantity = $this->pokemonRepository->getNumberOfPokemonByColor($color);

            $data[$color] = $quantity;
        }

        return $data;
    }
}
