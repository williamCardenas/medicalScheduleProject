<?php

namespace App\Repository;

use App\Entity\Medico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Medico|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medico|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medico[]    findAll()
 * @method Medico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Medico::class);
    }

//    /**
//     * @return Medico[] Returns an array of Medico objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Medico
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function search($params)
    {
        $qb = $this->createQueryBuilder('M');

        if(array_key_exists('cliente',$params) and  !empty($params['cliente'])){
            $qb->andWhere('M.cliente in(:clienteId)')
            ->setParameter('clienteId',$params['cliente']->getId());
        }
        $qb->orderBy('M.nome', 'ASC');

        return $qb;
    }
}
