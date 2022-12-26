<?php

namespace App\Command;

use App\Repository\PokemonRepository;
use App\Service\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:pokemon:import-height',
    description: 'import weight and height property of Pokemon from API pokeAPI and store them.',
)]
class PokemonImportWeightHeightCommand extends Command
{
    protected static $defaultName = 'app:pokemon:importWeightHeight';

    public function __construct(
        private readonly Client $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly PokemonRepository $pokemonRepo,
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
            if (isset($pokemonResponse['height'])) {
                $height = $pokemonResponse['height'];
                $pokemon->setHeight($height);
                $numberOfUpdate++;
            }
            if (isset($pokemonResponse['weight'])) {
                $weight = $pokemonResponse['weight'];
                $pokemon->setWeight($weight);
                $numberOfUpdate++;
            }

            $this->entityManager->flush();
            $progressBar->advance();
        }
        $progressBar->finish();


        $io->success(sprintf('Imported %d weight and height for %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
