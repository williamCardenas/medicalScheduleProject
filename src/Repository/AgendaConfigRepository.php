<?php

namespace App\Repository;

use App\Entity\AgendaConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AgendaConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgendaConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgendaConfig[]    findAll()
 * @method AgendaConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgendaConfig::class);
    }

//    /**
//     * @return AgendaConfig[] Returns an array of AgendaConfig objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AgendaConfig
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
