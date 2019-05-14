<?php

namespace App\Repository;

use App\Entity\AgendaDataStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AgendaDataStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgendaDataStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgendaDataStatus[]    findAll()
 * @method AgendaDataStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaDataStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AgendaDataStatus::class);
    }

//    /**
//     * @return Cliente[] Returns an array of Cliente objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cliente
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
