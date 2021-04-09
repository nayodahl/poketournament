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

class PokemonImportWeightHeightlCommand extends Command
{
    protected static $defaultName = 'app:pokemon:importWeightHeight';

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
            ->setDescription('import weight and height property of Pokemon from API pokeAPI and store them')
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

            for ($i = 1; $i < 4; $i++) {
                $pokemon = $this->pokemonRepo->findOneBy(['apiId' => $i]);
                $response = $this->client->request(
                    'GET',
                    'https://pokeapi.co/api/v2/pokemon/'.$i
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                if (isset($array['height'])) {
                    $height = $array['height'];
                    $pokemon?->setHeight($height);
                    $numberOfUpdate++;
                }
                if (isset($array['weight'])) {
                    $weight = $array['weight'];
                    $pokemon?->setWeight($weight);
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
                    'https://pokeapi.co/api/v2/pokemon/'.$pokemon->getApiId()
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                if (isset($array['height'])) {
                    $height = $array['height'];
                    $pokemon->setHeight($height);
                    $numberOfUpdate++;
                }
                if (isset($array['weight'])) {
                    $weight = $array['weight'];
                    $pokemon->setWeight($weight);
                    $numberOfUpdate++;
                }

                $this->entityManager->flush();
                $progressBar->advance();
            };
            $progressBar->finish();
        }

        $io->success(sprintf('Imported %d weight and height for %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
