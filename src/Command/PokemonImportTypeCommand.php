<?php

namespace App\Command;

use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonImportTypeCommand extends Command
{
    protected static $defaultName = 'app:pokemon:importType';

    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
        private PokemonRepository $pokemonRepo,
        private TypeRepository $typeRepo
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('import types of Pokemon from API pokeAPI and store them')
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

            for ($i = 4; $i < 10; $i++) {
                $pokemon = $this->pokemonRepo->findOneBy(['apiId' => $i]);
                $response = $this->client->request(
                    'GET',
                    'https://pokeapi.co/api/v2/pokemon/'.$i
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                //slot 1 on API, will be stored as type1 property
                if (isset($array['types'][0])) {
                    $type1Url = $array['types'][0]['type']['url'];
                    $response = $this->client->request(
                        'GET',
                        $type1Url
                    );
                    $content = $response->getContent();
                    $arrayType1=json_decode($content, true);
                    $type1 = $this->typeRepo->findOneBy(['name' =>  $arrayType1['names'][2]['name']]);
                    $pokemon->setType1($type1);

                    $numberOfUpdate++;
                }

                //slot 2 on API, will be stored as type2 property
                if (isset($array['types'][1])) {
                    $type2Url = $array['types'][1]['type']['url'];
                    $response = $this->client->request(
                        'GET',
                        $type2Url
                    );
                    $content = $response->getContent();
                    $arrayType2=json_decode($content, true);
                    $type2 = $this->typeRepo->findOneBy(['name' =>  $arrayType2['names'][2]['name']]);
                    $pokemon->setType2($type2);

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

                //slot 1 on API, will be stored as type1 property
                if (isset($array['types'][0])) {
                    $type1Url = $array['types'][0]['type']['url'];
                    $response = $this->client->request(
                        'GET',
                        $type1Url
                    );
                    $content = $response->getContent();
                    $arrayType1=json_decode($content, true);
                    $type1 = $this->typeRepo->findOneBy(['name' =>  $arrayType1['names'][2]['name']]);
                    $pokemon->setType1($type1);

                    $numberOfUpdate++;
                }

                //slot 2 on API, will be stored as type2 property
                if (isset($array['types'][1])) {
                    $type2Url = $array['types'][1]['type']['url'];
                    $response = $this->client->request(
                        'GET',
                        $type2Url
                    );
                    $content = $response->getContent();
                    $arrayType2=json_decode($content, true);
                    $type2 = $this->typeRepo->findOneBy(['name' =>  $arrayType2['names'][2]['name']]);
                    $pokemon->setType2($type2);

                    $numberOfUpdate++;
                }
                $this->entityManager->flush();
                $progressBar->advance();
            };
            $progressBar->finish();
        }

        $io->success(sprintf('Imported %d types for %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
