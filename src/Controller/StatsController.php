<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function dashboard(): Response
    {
        
        
        return $this->render('stats/dashboard.html.twig', [
            'controller_name' => 'StatsController',
        ]);
    }
}
