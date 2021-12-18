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

class PokemonImportDescriptionCommand extends Command
{
    protected static $defaultName = 'app:pokemon:importDescription';

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
            ->setDescription('import descriptions of Pokemon from API pokeAPI and store them')
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

            for ($i = 803; $i < 887; $i++) {
                $pokemon = $this->pokemonRepo->findOneBy(['apiId' => $i]);
                $response = $this->client->request(
                    'GET',
                    'https://pokeapi.co/api/v2/pokemon-species/'.$i
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                if (isset($array['flavor_text_entries'])) {
                    // we read entries of $array['flavor_text_entries'] backward,
                    // so we have the most recent entry (most likely from pokemon sword/shield)
                    // and we stop at first french entry we read
                    $keyNumber = count($array['flavor_text_entries']) - 1;
                    while (($keyNumber > 0) &&
                        ($array['flavor_text_entries'][$keyNumber]['language']['name'] !== 'fr')) {
                        $keyNumber--;
                    }
                    $description = $array['flavor_text_entries'][$keyNumber]['flavor_text'];
                    
                    if ($pokemon?->getDescription() !== $description) {
                        $numberOfUpdate++;
                    }
                }

                $progressBar->advance();
            };
            $progressBar->finish();
        } else {
            // creates a new progress bar (units = total number of pokemons fetched)
            $progressBar = new ProgressBar($output, $numberOfPokemon);

            foreach ($pokemons as $pokemon) {
                $response = $this->client->request(
                    'GET',
                    'https://pokeapi.co/api/v2/pokemon-species/'.$pokemon->getApiId()
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                if (isset($array['flavor_text_entries'])) {
                    // we read entries of $array['flavor_text_entries'] backward,
                    // so we have the most recent entry (most likely from pokemon sword/shield)
                    // and we stop at first french entry we read
                    $keyNumber = count($array['flavor_text_entries']) - 1;
                    while (($keyNumber > 0) &&
                        ($array['flavor_text_entries'][$keyNumber]['language']['name'] !== 'fr')) {
                        $keyNumber--;
                    }
                    $description = $array['flavor_text_entries'][$keyNumber]['flavor_text'];
                    
                    if ($pokemon->getDescription() !== $description) {
                        $numberOfUpdate++;
                        $pokemon->setDescription($description);
                        $this->entityManager->flush();
                    }
                }

                $progressBar->advance();
            };
            $progressBar->finish();
        }

        $io->success(sprintf('Imported %d evolutions from %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
