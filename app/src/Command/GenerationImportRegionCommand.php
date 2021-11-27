<?php

namespace App\Command;

use App\Entity\Generation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GenerationImportRegionCommand extends Command
{
    protected static $defaultName = 'app:generation:importRegion';
    
    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->setDescription('import region property of Generation and store them')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $numberOfUpdate=0;
        $numberOfType = 8;

        if ($input->getOption('dry-run')) {
            $io->note('Dry run mode enabled');
            $progressBar = new ProgressBar($output, $numberOfType);
           
            for ($i = 1; $i <= $numberOfType; $i++) {
                $response = $this->client->request(
                    'GET',
                    'https://pokeapi.co/api/v2/generation/'.$i
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                if (isset($array['main_region'])) {
                    $numberOfUpdate++;
                    $generationName = $array['main_region']['name'];
                    $generation = new Generation();
                    $generation->setRegion($generationName);
                    $generationApiId = $array['id'];
                    $generation->setApiId($generationApiId);
                }
                $progressBar->advance();
            };
            $progressBar->finish();
        } else {
            // creates a new progress bar (units = total number of pokemons fetched)
            $progressBar = new ProgressBar($output, $numberOfType);
           
            for ($i = 1; $i <= $numberOfType; $i++) {
                $response = $this->client->request(
                    'GET',
                    'https://pokeapi.co/api/v2/generation/'.$i
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                if (isset($array['main_region'])) {
                    $numberOfUpdate++;
                    $generationName = $array['main_region']['name'];
                    $generation = new Generation();
                    $generation->setRegion($generationName);
                    $generationApiId = $array['id'];
                    $generation->setApiId($generationApiId);
                    $this->entityManager->persist($generation);
                    $this->entityManager->flush();
                }
                $progressBar->advance();
            };
            $progressBar->finish();
        }

        $io->success(sprintf('Imported %d region names and apiId for %d generation.', $numberOfUpdate, $numberOfType));

        return Command::SUCCESS;
    }
}
