<?php

namespace App\Command;

use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonImportEvolutionCommand extends Command
{
    protected static $defaultName = 'app:pokemon:importEvolution';

    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
        private PokemonRepository $pokemonRepo,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('import evolutions of Pokemon from API pokeAPI and store them')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $pokemons = $this->pokemonRepo->findAll();
        $numberOfPokemon = count($pokemons);
        $numberOfUpdate = 0;

        if ($input->getOption('dry-run')) {
            $io->note('Dry run mode enabled');

            // creates a new progress bar (units = total number of pokemons fetched)
            $progressBar = new ProgressBar($output, $numberOfPokemon);

            for ($i = 1; $i < 10; $i++) {
                $pokemon = $this->pokemonRepo->findOneBy(['apiId' => $i]);
                $response = $this->pokeApiClient->request(
                    'GET',
                    'https://pokeapi.co/api/v2/pokemon-species/'.$i
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                if (isset($array['evolves_from_species'])) {
                    $numberOfUpdate++;
                }

                $progressBar->advance();
            };
            $progressBar->finish();
        } else {
            // creates a new progress bar (units = total number of pokemons fetched)
            $progressBar = new ProgressBar($output, $numberOfPokemon);

            foreach ($pokemons as $pokemon) {
                $response = $this->pokeApiClient->request(
                    'GET',
                    'https://pokeapi.co/api/v2/pokemon-species/'.$pokemon->getApiId()
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                // if evolves_from_species is not null, then it shows its parent
                if (isset($array['evolves_from_species'])) {
                    $parentUrl = $array['evolves_from_species']['url'];
                    $response = $this->pokeApiClient->request(
                        'GET',
                        $parentUrl
                    );
                    $content = $response->getContent();
                    $arrayParent=json_decode($content, true);
                    $parentApiId=$arrayParent['id'];
                    $parent = $this->pokemonRepo->findOneBy(['apiId' => $parentApiId]);
                    $pokemon->setParent($parent);
                    $this->entityManager->flush();
                    $numberOfUpdate++;
                }

                $progressBar->advance();
            };
            $progressBar->finish();
        }

        $io->success(sprintf('Imported %d evolutions from %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
