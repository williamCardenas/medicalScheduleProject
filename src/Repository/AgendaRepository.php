<?php

namespace App\Repository;

use App\Entity\Agenda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Agenda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agenda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agenda[]    findAll()
 * @method Agenda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Agenda::class);
    }

    /**
     * @return Agenda[] Returns an array of Agenda objects
     */
    public function findByParams(Array $params)
    {
        $qb = $this->createQueryBuilder('a');
        
        if(array_key_exists('medico',$params) && !empty($params['medico'])){
            $qb->andWhere('a.medico = :medico')
            ->setParameter('medico', $params['medico']);
        }

        if(array_key_exists('clinica',$params) && !empty($params['clinica'])){
            $qb->andWhere('a.clinica = :clinica')
            ->setParameter('clinica', $params['clinica']);
        }

        $qb->orderBy('a.id', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
        ;
        
        return $qb->getQuery()->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Agenda
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
