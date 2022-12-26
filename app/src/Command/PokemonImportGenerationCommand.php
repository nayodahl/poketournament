<?php

namespace App\Command;

use App\Repository\GenerationRepository;
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
    name: ' ',
    description: 'import generation of Pokemons and store them.',
)]
class PokemonImportGenerationCommand extends Command
{
    public function __construct(
        private readonly Client                 $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly PokemonRepository      $pokemonRepo,
        private readonly GenerationRepository   $generationRepo
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

            if (isset($specie['generation']['url'])) {
                $generation = $this->client->get($specie['generation']['url']);
                $pokemonGeneration = $this->generationRepo->findOneBy(['apiId' =>  $generation['id']]);

                if ($pokemon->getGeneration() !== $pokemonGeneration) {
                    $pokemon->setGeneration($pokemonGeneration);
                    $this->entityManager->flush();
                    $numberOfUpdate++;
                }
            }
            $progressBar->advance();
        }
        $progressBar->finish();
        $io->success(sprintf('Imported %d generations for %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
