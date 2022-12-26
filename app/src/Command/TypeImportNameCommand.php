<?php

namespace App\Command;

use App\Entity\Type;
use App\Repository\TypeRepository;
use App\Service\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:type:import-names',
    description: 'import name property of Types and store them.',
)]
class TypeImportNameCommand extends Command
{
    public function __construct(
        private readonly Client $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly TypeRepository $typeRepo
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $numberOfUpdate=0;
        $typesResponse = $this->client->get('api/v2/type');
        $numberOfType = $typesResponse['count'];
        $typesList = $typesResponse['results'];

        $progressBar = new ProgressBar($output, $numberOfType);

        foreach ($typesList as $type) {
            $typeResponse = $this->client->get($type['url']);

            foreach ($typeResponse['names'] as $typeName) {
                if ($typeName['language']['name'] !== 'fr') {
                    continue;
                }

                if (null === $this->typeRepo->findOneBy(['apiId' => $typeResponse['id']])) {
                     $type = new Type();
                     $type->setName($typeName['name']);
                     $type->setApiId($typeResponse['id']);
                    $numberOfUpdate++;
                    $this->entityManager->persist($type);
                    $this->entityManager->flush();
                }
            }
            $progressBar->advance();
        }
        $progressBar->finish();

        $io->success(sprintf('Updated %d types name for %d types.', $numberOfUpdate, $numberOfType));

        return Command::SUCCESS;
    }
}
