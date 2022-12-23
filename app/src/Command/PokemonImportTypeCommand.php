<?php

namespace App\Command;

use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use App\Service\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:pokemon:import-type',
    description: 'import types of Pokemon from API pokeAPI and store them.',
)]
class PokemonImportTypeCommand extends Command
{
    public function __construct(
        private readonly Client $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly PokemonRepository $pokemonRepo,
        private readonly TypeRepository $typeRepo
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $pokemons = $this->pokemonRepo->findAll();
        $numberOfPokemon = count($pokemons);
        $numberOfUpdate = 0;
        $progressBar = new ProgressBar($output, $numberOfPokemon);

        foreach ($pokemons as $pokemon) {
            $pokemonResponse = $this->client->get('api/v2/pokemon/'.$pokemon->getApiId());

            if (isset($pokemonResponse['types'][0])) {
                $type1Url = $pokemonResponse['types'][0]['type']['url'];
                $arrayType1 = $this->client->get($type1Url);
                $type1 = $this->typeRepo->findOneBy(['name' =>  $arrayType1['names'][2]['name']]);
                $pokemon->setType1($type1);

                $numberOfUpdate++;
            }

            if (isset($pokemonResponse['types'][1])) {
                $type2Url = $pokemonResponse['types'][1]['type']['url'];
                $arrayType2 = $this->client->get($type2Url);
                $type2 = $this->typeRepo->findOneBy(['name' => $arrayType2['names'][2]['name']]);
                $pokemon->setType2($type2);

                $numberOfUpdate++;
            }
            $this->entityManager->flush();
            $progressBar->advance();
        }
        $progressBar->finish();
        $io->success(sprintf('Imported %d types for %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
