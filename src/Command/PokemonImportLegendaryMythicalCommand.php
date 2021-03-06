<?php

namespace App\Command;

use App\Repository\GenerationRepository;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonImportLegendaryMythicalCommand extends Command
{
    protected static $defaultName = 'app:pokemon:importLegendaryMythical';

    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
        private PokemonRepository $pokemonRepo,
        private GenerationRepository $generationRepo
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('import legendary and mythical property of Pokemon from API pokeAPI and store them')
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
            $progressBar = new ProgressBar($output, $numberOfPokemon);

            for ($i = 893; $i < 895; $i++) {
                $pokemon = $this->pokemonRepo->findOneBy(['apiId' => $i]);
                $response = $this->client->request(
                    'GET',
                    'https://pokeapi.co/api/v2/pokemon-species/'.$i
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                if (isset($array['is_legendary'])) {
                    $isLegendary = $array['is_legendary'];
                    $pokemon?->setLegendary($isLegendary);
                    $numberOfUpdate++;
                }
                if (isset($array['is_mythical'])) {
                    $isMythical = $array['is_mythical'];
                    $pokemon?->setMythical($isMythical);
                    $numberOfUpdate++;
                }
                $progressBar->advance();
            };
            $progressBar->finish();
        } else {
            $progressBar = new ProgressBar($output, $numberOfPokemon);

            foreach ($pokemons as $pokemon) {
                $response = $this->client->request(
                    'GET',
                    'https://pokeapi.co/api/v2/pokemon-species/'.$pokemon->getApiId()
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                if (isset($array['is_legendary'])) {
                    $isLegendary = $array['is_legendary'];
                    $pokemon->setLegendary($isLegendary);
                    $numberOfUpdate++;
                }
                if (isset($array['is_mythical'])) {
                    $isMythical = $array['is_mythical'];
                    $pokemon->setMythical($isMythical);
                    $numberOfUpdate++;
                }

                $this->entityManager->flush();
                $progressBar->advance();
            };
            $progressBar->finish();
        }

        $io->success(sprintf('Imported %d legendary infos for %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
