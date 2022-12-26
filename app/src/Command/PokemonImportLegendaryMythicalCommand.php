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
    name: 'app:pokemon:import-legendary',
    description: 'import legendary and mythical property of Pokemon and store them.',
)]
class PokemonImportLegendaryMythicalCommand extends Command
{
    public function __construct(
        private readonly Client                 $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly PokemonRepository      $pokemonRepo,
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
            $specie = $this->client->get('api/v2/pokemon-species/'.$pokemon->getApiId());

            if (isset($specie['is_legendary'])) {
                $pokemon->setLegendary($specie['is_legendary']);
                $numberOfUpdate++;
            }
            if (isset($specie['is_mythical'])) {
                $pokemon->setMythical($specie['is_mythical']);
                $numberOfUpdate++;
            }

            $this->entityManager->flush();
            $progressBar->advance();
        }
        $progressBar->finish();
        $io->success(sprintf('Imported or refreshed %d legendary or mythical properties for %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
