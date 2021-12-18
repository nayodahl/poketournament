<?php

namespace App\Command;

use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TypeImportNameCommand extends Command
{
    protected static $defaultName = 'app:type:importName';
    
    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->setDescription('import name property of Types and store them')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $numberOfUpdate=0;
        $numberOfType = 18;

        if ($input->getOption('dry-run')) {
            $io->note('Dry run mode enabled');
            $progressBar = new ProgressBar($output, $numberOfType);
           
            for ($i = 1; $i <= $numberOfType; $i++) {
                $response = $this->client->request(
                    'GET',
                    'https://pokeapi.co/api/v2/type/'.$i
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                if (isset($array['names'])) {
                    $numberOfUpdate++;
                    $typeName = $array['names'][2]['name'];
                    $type = new Type();
                    $type->setName($typeName);
                    //$this->entityManager->persist($type);
                    //$this->entityManager->flush;
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
                    'https://pokeapi.co/api/v2/type/'.$i
                );
                $content = $response->getContent();
                $array=json_decode($content, true);

                if (isset($array['names'])) {
                    $numberOfUpdate++;
                    $typeName = $array['names'][2]['name'];
                    $type = new Type();
                    $type->setName($typeName);
                    $this->entityManager->persist($type);
                    $this->entityManager->flush();
                }
                $progressBar->advance();
            };
            $progressBar->finish();
        }

        $io->success(sprintf('Updated %d types name for %d types.', $numberOfUpdate, $numberOfType));

        return Command::SUCCESS;
    }
}
