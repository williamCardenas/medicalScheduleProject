<?php

namespace App\Repository;

use App\Entity\Paciente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Paciente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paciente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paciente[]    findAll()
 * @method Paciente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PacienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paciente::class);
    }
    public function search($params)
    {
        $qb = $this->createQueryBuilder('P');

        if(array_key_exists('cliente',$params) and  !empty($params['cliente'])){
            $qb->andWhere('P.cliente in(:clienteId)')
            ->setParameter('clienteId',$params['cliente']->getId());
        }

        if(array_key_exists('pesquisa',$params) and  !empty($params['pesquisa'])){
            $orModule = $qb->expr()->like('P.nome',':pesquisa');
        
            $qb->setParameter('pesquisa','%'.$params['pesquisa'].'%');
            
            $qb->andWhere( $orModule);
        }

        $qb->orderBy('P.nome', 'ASC');

        return $qb;
    }

    public function searchResult($params)
    {
        $qb = $this->search($params);
        return $qb->getQuery()->getResult();
    }

    public function searchArrayResult($params = Array())
    {
        $qb = $this->search($params);
        $qb->select('P','P.id','P.nome as text');
        return $qb->getQuery()->getArrayResult();
    }
}
