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

    public function findOneById($id, User $owner): ?Network
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

    // /**
    //  * @return Network[] Returns an array of Network objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Network
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}