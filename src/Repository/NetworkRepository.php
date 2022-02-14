<?php

namespace App\Repository;

use App\Entity\Network;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Network|null find($id, $lockMode = null, $lockVersion = null)
 * @method Network|null findOneBy(array $criteria, array $orderBy = null)
 * @method Network[]    findAll()
 * @method Network[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NetworkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Network::class);
    }

    public function findOneByIdAndUser($id, User $owner): ?Network
    {
        return $this->createQueryBuilder('n')
            ->join('n.owner', 'o')
            ->andWhere('n.id = :id')
            ->setParameter('id', $id)
            ->andWhere('o.id = :owner')
            ->setParameter('owner', $owner->getId())
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findOneById($id): ?Network
    {
        return $this->createQueryBuilder('n')
            ->join('n.owner', 'o')
            ->andWhere('n.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
    public function findOneByQueue($queue): ?Network
    {
        return $this->createQueryBuilder('n')
            ->join('n.owner', 'o')
            ->andWhere('n.queue = :queue')
            ->setParameter('queue', $queue)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
