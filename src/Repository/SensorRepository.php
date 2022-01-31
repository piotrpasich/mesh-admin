<?php

namespace App\Repository;

use App\Entity\Sensor;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sensor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sensor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sensor[]    findAll()
 * @method Sensor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SensorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sensor::class);
    }

    /**
     * @param string $networkId
     * @return array Sensor[]
     */
    public function getSensorsInNetwork(string $networkId): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.network', 'n')
            ->join('n.owner', 'o')
            ->andWhere('s.id = :id')
            ->setParameter('id', $networkId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getById(int $sensorId): ?Sensor
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :id')
            ->setParameter('id', $sensorId)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
