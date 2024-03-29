<?php

namespace App\DataFixtures;

use App\Entity\Generation;
use App\Entity\Pokemon;
use App\Entity\Tournament;
use App\Entity\Type;
use App\Service\Initializor;
use App\Service\Slugger;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PokemonFixtures extends Fixture
{
    public function __construct(
        private readonly Initializor $initializor,
        private readonly Slugger $slugger
    ) {
    }
    
    public function load(ObjectManager $manager): void
    {
        // create 8 first pokemons
        $pokemon1 = new Pokemon();
        $pokemon1->setName('Bulbizarre');
        $pokemon1->setApiId(1);
        $pokemon1->setColor('blue');
        $pokemon1->setSlug($this->slugger->slugIt($pokemon1->getName()));
        $pokemon1->setDescription('Description de Bulbizarre');
        $pokemon1->setLegendary(true);
        $pokemon1->setMythical(true);
        $pokemon1->setHeight(10);
        $pokemon1->setWeight(50);
        $manager->persist($pokemon1);

        $pokemon2 = new Pokemon();
        $pokemon2->setName('Herbizarre');
        $pokemon2->setApiId(2);
        $pokemon2->setColor('blue');
        $pokemon2->setSlug($this->slugger->slugIt($pokemon2->getName()));
        $pokemon2->setDescription('Description de Herbizarre');
        $pokemon2->setParent($pokemon1);
        $pokemon2->setLegendary(true);
        $pokemon2->setMythical(true);
        $pokemon2->setHeight(10);
        $pokemon2->setWeight(50);
        $manager->persist($pokemon2);

        $pokemon3 = new Pokemon();
        $pokemon3->setName('Florizarre');
        $pokemon3->setApiId(3);
        $pokemon3->setColor('blue');
        $pokemon3->setSlug($this->slugger->slugIt($pokemon3->getName()));
        $pokemon3->setParent($pokemon2);
        $pokemon3->setLegendary(true);
        $pokemon3->setMythical(true);
        $pokemon3->setHeight(10);
        $pokemon3->setWeight(50);
        $manager->persist($pokemon3);

        $type1 = new Type();
        $type1->setName('Feu');
        $type1->setApiId(10);
        $manager->persist($type1);

        $generation1 = new Generation();
        $generation1->setApiId(1);
        $generation1->setRegion('kanto');
        $manager->persist($generation1);

        $pokemon4 = new Pokemon();
        $pokemon4->setName('Salamèche');
        $pokemon4->setApiId(4);
        $pokemon4->setColor('blue');
        $pokemon4->setSlug($this->slugger->slugIt($pokemon4->getName()));
        $pokemon4->setDescription('Description de Salamèche');
        $pokemon4->setType1($type1);
        $pokemon4->setGeneration($generation1);
        $pokemon4->setLegendary(true);
        $pokemon4->setMythical(true);
        $pokemon4->setHeight(90);
        $pokemon4->setWeight(80);
        $manager->persist($pokemon4);

        $pokemon5 = new Pokemon();
        $pokemon5->setName('Reptincel');
        $pokemon5->setApiId(5);
        $pokemon5->setColor('blue');
        $pokemon5->setSlug($this->slugger->slugIt($pokemon5->getName()));
        $pokemon5->setParent($pokemon4);
        $pokemon5->setLegendary(true);
        $pokemon5->setMythical(true);
        $pokemon5->setHeight(10);
        $pokemon5->setWeight(50);
        $manager->persist($pokemon5);

        $pokemon6 = new Pokemon();
        $pokemon6->setName('Dracaufeu');
        $pokemon6->setApiId(6);
        $pokemon6->setColor('blue');
        $pokemon6->setSlug($this->slugger->slugIt($pokemon6->getName()));
        $pokemon6->setParent($pokemon5);
        $pokemon6->setLegendary(true);
        $pokemon6->setMythical(true);
        $pokemon6->setHeight(10);
        $pokemon6->setWeight(50);
        $manager->persist($pokemon6);

        $pokemon7 = new Pokemon();
        $pokemon7->setName('Carapuce');
        $pokemon7->setApiId(7);
        $pokemon7->setColor('blue');
        $pokemon7->setSlug($this->slugger->slugIt($pokemon7->getName()));
        $pokemon7->setLegendary(true);
        $pokemon7->setMythical(true);
        $pokemon7->setHeight(10);
        $pokemon7->setWeight(50);
        $manager->persist($pokemon7);

        $pokemon8 = new Pokemon();
        $pokemon8->setName('Carabaffe');
        $pokemon8->setApiId(8);
        $pokemon8->setColor('blue');
        $pokemon8->setSlug($this->slugger->slugIt($pokemon8->getName()));
        $pokemon8->setParent($pokemon7);
        $pokemon8->setLegendary(true);
        $pokemon8->setMythical(true);
        $pokemon8->setHeight(10);
        $pokemon8->setWeight(50);
        $manager->persist($pokemon8);

        $manager->flush();

        // create a tournament with these 8 Pokémon
        $tournament = new Tournament();
        $tournament->setName('Test Tournament');
        $tournament->setDate(new DateTime());
        $tournament->setNumberPokemons(8);
        $tournament->addPokemon($pokemon1);
        $tournament->addPokemon($pokemon2);
        $tournament->addPokemon($pokemon3);
        $tournament->addPokemon($pokemon4);
        $tournament->addPokemon($pokemon5);
        $tournament->addPokemon($pokemon6);
        $tournament->addPokemon($pokemon7);
        $tournament->addPokemon($pokemon8);

        //init tournament
        $this->initializor->initTournament($tournament);

        $manager->persist($tournament);
        $manager->flush();
    }
}
