<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }
  
    public function findOneByNumberAndTournament(int $value, int $tournamentId): ?Game
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.number = :val')
            ->andWhere('g.tournament = :id')
            ->setParameter('val', $value)
            ->setParameter('id', $tournamentId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getNumberOfWonGames(int $pokemonId): int
    {
        return $this->createQueryBuilder('g')
        ->join('g.winner', 'p')
        ->where('p.id = :pokemon_id')
        ->setParameter('pokemon_id', $pokemonId)
        ->select('COUNT(g.winner) as wins')
        ->getQuery()
        ->getSingleScalarResult();
    }
}
