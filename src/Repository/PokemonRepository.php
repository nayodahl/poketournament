<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<Pokemon>
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
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
            ->getSingleScalarResult();
        ;
    }
}
