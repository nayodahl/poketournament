<?php

namespace App\Controller;

use App\Service\StatsCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class StatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function dashboard(ChartBuilderInterface $chartBuilder, StatsCalculator $statsCalculator): Response
    {
        ////// chart 1 /////
        $data = $statsCalculator->getMostUsedPokemons();
        $names = array_keys($data);
        $values = array_values($data);
        
        //setup of the chart for most used Pokemons
        $chartMostUsedPokemons = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chartMostUsedPokemons->setData([
            'labels' => $names,
            'datasets' => [
                [
                    'label' => 'Participations',
                    'backgroundColor' => 'rgb(23, 162, 184)',
                    'borderColor' => 'rgb(23, 162, 184)',
                    'data' => $values,
                ],
            ],
        ]);

        $chartMostUsedPokemons->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 10]],
                ],
            ],
        ]);

        ////// chart 2 /////
        $data = $statsCalculator->getMostWonGamesByPokemon();
        $names = array_keys($data);
        $values = array_values($data);
        
        //setup of the chart for most matchs won
        $chartMostWonGamesPokemons = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chartMostWonGamesPokemons->setData([
            'labels' => $names,
            'datasets' => [
                [
                    'label' => 'Victoires',
                    'backgroundColor' => 'rgb(23, 162, 184)',
                    'borderColor' => 'rgb(23, 162, 184)',
                    'data' => $values,
                ],
            ],
        ]);

        $chartMostWonGamesPokemons->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 10]],
                ],
            ],
        ]);

        ////// chart 3 /////
        $data = $statsCalculator->getPointsByTournament();
        $names = array_keys($data);
        $values = array_values($data);
        
        //setup of the chart for most matchs won
        $chartPointsByTournament = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chartPointsByTournament->setData([
            'labels' => $names,
            'datasets' => [
                [
                    'label' => 'Points',
                    'backgroundColor' => 'rgb(159, 227, 237)',
                    'borderColor' => 'rgb(23, 162, 184)',
                    'data' => $values,
                ],
            ],
        ]);

        $chartPointsByTournament->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0]],
                ],
            ],
        ]);

        ////// chart 4 /////
        $data = $statsCalculator->getPokemonByColor();
        $names = array_keys($data);
        $values = array_values($data);
        
        //setup of the chart for most matchs won
        $chartPokemonColors = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chartPokemonColors->setData([
            'labels' => $names,
            'datasets' => [
                [
                    'label' => 'Points',
                    'backgroundColor' => [
                        'rgba(255, 255, 255)',
                        'rgba(54, 162, 235)',
                        'brown',
                        'rgba(128, 128, 128)',
                        'rgba(255, 255, 0)',
                        'rgba(0, 0, 0)',
                        'rgba(255, 192, 203)',
                        'red',
                        'green',
                        'violet',
                    ],
                    'data' => $values,
                ],
            ],
        ]);

        $chartPokemonColors->setOptions([
            'legend' => [
                'display' => false,
            ],
        ]);

        return $this->render('stats/dashboard.html.twig', [
            'chartMostUsedPokemons' => $chartMostUsedPokemons,
            'chartMostWonGamesPokemons' => $chartMostWonGamesPokemons,
            'chartPointsByTournament' => $chartPointsByTournament,
            'chartPokemonColors' => $chartPokemonColors
        ]);
    }
}
