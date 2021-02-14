<?php

namespace App\Repository;

use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tournament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournament[]    findAll()
 * @method Tournament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournament::class);
    }

    public function findLatest()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    
    /**
     * Get the total number of participations of a pokemon to Tournaments
     *
     * @param int the id of the pokemon
     *
     * @return int the number of participations of a pokemon.
     *
     */
    
    public function getNumberOfParticipation(int $pokemonId): int
    {
      
        return $this->createQueryBuilder('t')
            ->join('t.Pokemons', 'p')
            ->where('p.id = :pokemon_id')
            ->setParameter('pokemon_id', $pokemonId)
            ->select('COUNT(t.id) as participations')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
