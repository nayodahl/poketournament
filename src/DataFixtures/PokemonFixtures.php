<?php

namespace App\DataFixtures;

use App\Entity\Pokemon;
use App\Entity\Tournament;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PokemonFixtures extends Fixture
{
    public function __construct()
    {
    }
    
    public function load(ObjectManager $manager)
    {
        // create 8 first pokemons
        $pokemon1 = new Pokemon();
        $pokemon1->setName('Bulbizarre');
        $pokemon1->setApiId('1');
        $pokemon1->setColor('blue');
        $manager->persist($pokemon1);

        $pokemon2 = new Pokemon();
        $pokemon2->setName('Herbizarre');
        $pokemon2->setApiId('2');
        $pokemon2->setColor('blue');
        $manager->persist($pokemon2);

        $pokemon3 = new Pokemon();
        $pokemon3->setName('Florizarre');
        $pokemon3->setApiId('3');
        $pokemon3->setColor('blue');
        $manager->persist($pokemon3);

        $pokemon4 = new Pokemon();
        $pokemon4->setName('Salamèche');
        $pokemon4->setApiId('4');
        $pokemon4->setColor('blue');
        $manager->persist($pokemon4);

        $pokemon5 = new Pokemon();
        $pokemon5->setName('Reptincel');
        $pokemon5->setApiId('5');
        $pokemon5->setColor('blue');
        $manager->persist($pokemon5);

        $pokemon6 = new Pokemon();
        $pokemon6->setName('Dracaufeu');
        $pokemon6->setApiId('6');
        $pokemon6->setColor('blue');
        $manager->persist($pokemon6);

        $pokemon7 = new Pokemon();
        $pokemon7->setName('Carapuce');
        $pokemon7->setApiId('7');
        $pokemon7->setColor('blue');
        $manager->persist($pokemon7);

        $pokemon8 = new Pokemon();
        $pokemon8->setName('Carabaffe');
        $pokemon8->setApiId('8');
        $pokemon8->setColor('blue');
        $manager->persist($pokemon8);

        $manager->flush();

        // create a tournament with these 8 pokemons
        $tournament = new Tournament();
        $tournament->setName('Test Tournament');
        $tournament->setDate(new DateTime());
        $tournament->setNumberPokemons('8');
        $tournament->addPokemon($pokemon1);
        $tournament->addPokemon($pokemon2);
        $tournament->addPokemon($pokemon3);
        $tournament->addPokemon($pokemon4);
        $tournament->addPokemon($pokemon5);
        $tournament->addPokemon($pokemon6);
        $tournament->addPokemon($pokemon7);
        $tournament->addPokemon($pokemon8);
        $manager->persist($tournament);

        $manager->flush();
    }
}
