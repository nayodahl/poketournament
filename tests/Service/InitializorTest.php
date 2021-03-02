<?php

namespace App\Tests\Service;

use App\Entity\Tournament;
use App\Repository\GameRepository;
use App\Repository\PokemonRepository;
use App\Repository\TournamentRepository;
use App\Service\Initializor;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Translation\Dumper\IniFileDumper;

class InitializorTest extends KernelTestCase
{  
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->initializor = self::$container->get('App\Service\Initializor');
    }
    
    public function testInitTournament()
    {
        // load a created tournament and init it
        $tournament = $this->entityManager
        ->getRepository(Tournament::class)
        ->findOneBy(['name' => 'Test Tournament'])
        ;
       
        $this->initializor->initTournament($tournament);

        // load a created game
        $gameRepository = static::$container->get(GameRepository::class);
        $game = $gameRepository->findOneBy([], ['id'=>'DESC'], 1, 0);
        
        $this->assertEquals($tournament->getId(), $game->getTournament()->getId());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}