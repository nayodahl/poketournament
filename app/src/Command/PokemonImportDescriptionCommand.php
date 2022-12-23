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
    name: 'app:generation:import-description',
    description: 'import descriptions of Pokemon from API pokeAPI and store them.',
)]
class PokemonImportDescriptionCommand extends Command
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
            if (isset($specie['flavor_text_entries'])) {
                // we read entries of array backward,
                // so we have the most recent entry (most likely from pokemon sword/shield)
                // and we stop at first french entry we read
                $keyNumber = count($specie['flavor_text_entries']) - 1;
                while (($keyNumber > 0) &&
                    ($specie['flavor_text_entries'][$keyNumber]['language']['name'] !== 'fr')) {
                    $keyNumber--;
                }
                $description = $specie['flavor_text_entries'][$keyNumber]['flavor_text'];

                if ($pokemon->getDescription() !== $description) {
                    $numberOfUpdate++;
                    $pokemon->setDescription($description);
                    $this->entityManager->flush();
                }
            }

            $progressBar->advance();
        }
        $progressBar->finish();

        $io->success(sprintf('Imported or refreshed %d descriptions from %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
