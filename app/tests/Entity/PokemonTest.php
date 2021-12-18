<?php

namespace App\Tests\Entity;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PokemonTest extends WebTestCase
{
    private PokemonRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->repository = static::getContainer()->get('app.repository.pokemonrepository');
    }

    public function testParentingRelationBetweenPokemons(): void
    {
        // load Herbizarre, it was created by datafixtures (he is level 1 of the tree of this relation)
        $pokemon1 = $this->repository->findOneBy(['name' => 'Herbizarre']);

        // check that it's parent is Bulbizzare (root of the tree of this relation)
        $this->assertEquals( 'Bulbizarre', $pokemon1->getParent()->getName());

        // load Florizarre (he is level 2 of the tree of this relation)
        $pokemon2 = $this->repository->findOneBy(['name' => 'Florizarre']);

        // check that it's parent is Herbizarre
        $this->assertEquals($pokemon2->getParent(), $pokemon1);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
