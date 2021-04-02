<?php

namespace App\Tests\Entity;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PokemonTest extends WebTestCase
{
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    
    public function testParentingRelationBetweenPokemons(): void
    {
        // load Herbizarre, it was created by datafixtures (he is level 1 of the tree of this relation)
        $pokemon1 = static::$container->get(PokemonRepository::class)->findOneBy(['name' => 'Herbizarre']);

        // check that it's parent is Bulbizzare (root of the tree of this relation)
        $this->assertEquals($pokemon1->getParent()->getName(), 'Bulbizarre');

        // load Florizarre (he is level 2 of the tree of this relation)
        $pokemon2 = static::$container->get(PokemonRepository::class)->findOneBy(['name' => 'Florizarre']);

        // check that it's parent is Herbizarre
        $this->assertEquals($pokemon2->getParent(), $pokemon1);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}