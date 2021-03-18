<?php

namespace App\Tests\Service;

use App\Entity\Tournament;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Persistence\ObjectManager;

class InitializorTest extends KernelTestCase
{
    /**
     * @var \Doctrine\Persistence\ObjectManager
     */
    private ObjectManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    
    public function testInitTournament()
    {
        // load a created tournament and init it, it was created by datafixtures
        $tournament = $this->entityManager->getRepository(Tournament::class)->findOneBy(['name' => 'Test Tournament']);
       
        // load the most recently created game
        $gameRepository = static::$container->get(GameRepository::class);
        $game = $gameRepository->findOneBy([], ['id'=>'DESC']);
        
        // check that it is now linked to correct tournament
        $this->assertEquals($tournament->getId(), $game->getTournament()->getId());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
