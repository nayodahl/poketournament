<?php

namespace App\Command;

use App\Entity\Generation;
use App\Service\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generation:import-region',
    description: 'import region property of Generation and store them.',
)]
class GenerationImportRegionCommand extends Command
{
    public function __construct(
        private Client $client,
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $numberOfUpdate=0;

        $generations = $this->client->get( 'api/v2/generation');
        $numberOfGeneration = $generations['count'] ?? 8;
        $progressBar = new ProgressBar($output, $numberOfGeneration);


        for ($i = 1; $i <= $numberOfGeneration; $i++) {
            $response = $this->client->get(sprintf('api/v2/generation/%d', $i));
            if (isset($response['main_region'])) {
                $generation = new Generation();
                $generation->setRegion($response['main_region']['name']);
                $generation->setApiId($response['id']);

                if (null === ($this->entityManager->getRepository(Generation::class)->findOneBy(['apiId' => $generation->getApiId()]))){
                    $this->entityManager->persist($generation);
                    $this->entityManager->flush();
                    $numberOfUpdate++;
                }

            }
            $progressBar->advance();
        }
        $progressBar->finish();

        $io->success(sprintf('Imported %d region names and apiId for %d generation.', $numberOfUpdate, $numberOfGeneration));

        return Command::SUCCESS;
    }
}
