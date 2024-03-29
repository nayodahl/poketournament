<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonRepository extends NestedTreeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $manager = $registry->getManagerForClass(Pokemon::class);

        parent::__construct($manager, $manager->getClassMetadata(Pokemon::class));
    }

    /**
     * @return array<int, Pokemon>
     */
    public function findAllAlphabeticalMatching(?string $query, int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->setMaxResults($limit)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findPreviousByApiId(Pokemon $pokemon): ?Pokemon
    {
        $apiId = $pokemon->getApiId();

        // previous of number one should be the last one of all pokemons
        if ($apiId === 1) {
            $totalNumberOfPokemon = count($this->findAll());
            $apiId = $totalNumberOfPokemon + 1;
        }

        $query = $this->createQueryBuilder('p')
        ->where('p.apiId = :query')
        ->setParameter('query', $apiId - 1)
        ->getQuery()
        ;

        return $query->setMaxResults(1)->getOneOrNullResult();
    }

    public function findNextByApiId(Pokemon $pokemon): ?Pokemon
    {
        $apiId = $pokemon->getApiId();
        $totalNumberOfPokemon = count($this->findAll());

        // next of last pokemon should be the number one
        if ($apiId === $totalNumberOfPokemon) {
            $apiId = 0;
        }

        $query = $this->createQueryBuilder('p')
        ->where('p.apiId = :query')
        ->setParameter('query', $apiId + 1)
        ->getQuery()
        ;

        return $query->setMaxResults(1)->getOneOrNullResult();
    }

    /**
     * @return array<int, array>
     */
    public function getAllDistinctColors(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.color')
            ->distinct()
            ->orderBy('p.color', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getNumberOfPokemonByColor(string $color): int
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.color = :color')
            ->setParameter('color', $color)
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}
