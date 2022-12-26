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
    name: 'app:pokemon:import-evolution',
    description: 'import evolutions of Pokemon from API pokeAPI and store them.',
)]
class PokemonImportEvolutionCommand extends Command
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
            $evolutions = $this->client->get('api/v2/pokemon-species/'.$pokemon->getApiId());

            // if evolves_from_species is not null, then it shows its parent
            if (isset($evolutions['evolves_from_species'])) {
                $parent = $this->client->get($evolutions['evolves_from_species']['url']);
                $parent = $this->pokemonRepo->findOneBy(['apiId' => $parent['id']]);
                if ($parent !== $pokemon->getParent()) {
                    $pokemon->setParent($parent);
                    $this->entityManager->flush();
                    $numberOfUpdate++;
                }
            }
            $progressBar->advance();
        }
        $progressBar->finish();

        $io->success(sprintf('Imported %d evolutions from %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
