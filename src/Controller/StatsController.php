<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use App\Repository\TournamentRepository;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\ControlStructures\ForEachLoopDeclarationSniff;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
class StatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function dashboard(ChartBuilderInterface $chartBuilder, PokemonRepository $pokemonRepository, TournamentRepository $tournamentRepository): Response
    {
        // select all tournaments
        $tournaments = $tournamentRepository->findAll();     

        $data=[];
        // get number of participations for each pokemon
        foreach ($tournaments as $tournament){
            foreach ($tournament->getPokemons() as $pokemon){
                $numberOfParticipation = $tournamentRepository->getNumberOfParticipation($pokemon->getId());
                $data[$pokemon->getName()] = $numberOfParticipation;
            }
        }

        $names = array_keys($data);
        $values = array_values($data);
        
        //setup of the chart
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
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

        $chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 10]],
                ],
            ],
        ]);

        return $this->render('stats/dashboard.html.twig', [
            'chart' => $chart,
        ]);
    }
}
