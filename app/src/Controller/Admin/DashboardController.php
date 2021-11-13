<?php

namespace App\Controller\Admin;

use App\Controller\PokeController;
use App\Entity\Game;
use App\Entity\Pokemon;
use App\Entity\Tournament;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(TournamentCrudController::class)->generateUrl());
        
        //return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Poketournament')
            ->setFaviconPath('build/img/ico/favicon.ico')
            ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToRoute('Exit', 'fa fa-window-close-o', 'app_homepage'),

            MenuItem::section('Pokemons'),
            MenuItem::linkToCrud('Pokemons', 'fa fa-tags', Pokemon::class),

            MenuItem::section('Tournaments'),
            MenuItem::linkToCrud('Games', 'fa fa-tags', Game::class)
            ->setDefaultSort(['createdAt' => 'DESC']),
            MenuItem::linkToCrud('Tournaments', 'fa fa-tags', Tournament::class)
            ->setDefaultSort(['date' => 'DESC']),

        ];
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
