<?php

namespace App\Command;

use App\Repository\PokemonRepository;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:pokemon:refresh-slug',
    description: 'refresh slug property of Pokemons and store them.',
)]
class PokemonRefreshSlugCommand extends Command
{
    public function __construct(
        private PokemonRepository $pokemonRepo,
        private EntityManagerInterface $entityManager,
        private Slugger $slugger
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
            $previousSlug = $pokemon->getSlug();
            $newSlug = $this->slugger->slugIt($pokemon->getName());

            if ($newSlug !== $previousSlug) {
                $pokemon->setSlug($newSlug);
                $this->entityManager->flush();
                $numberOfUpdate++;
            }
            $progressBar->advance();
        }
        $progressBar->finish();

        $io->success(sprintf('Updated %d slugs from %d pokemons.', $numberOfUpdate, $numberOfPokemon));

        return Command::SUCCESS;
    }
}
