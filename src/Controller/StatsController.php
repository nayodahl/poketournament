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
        $data = $statsCalculator->getMostUsedPokemons();
        $names = array_keys($data);
        $values = array_values($data);
        
        //setup of the chart for most matchs won
        $chartMostWonGamesPokemons = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chartMostWonGamesPokemons->setData([
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

        $chartMostWonGamesPokemons->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 10]],
                ],
            ],
        ]);

        return $this->render('stats/dashboard.html.twig', [
            'chartMostUsedPokemons' => $chartMostUsedPokemons,
            'chartMostWonGamesPokemons' => $chartMostWonGamesPokemons
        ]);
    }
}
