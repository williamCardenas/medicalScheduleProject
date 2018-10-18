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

    public function searchResult($params)
    {
        $qb = $this->search($params);
        return $qb->getQuery()->getResult();
    }

    /**
     * @return Agenda[] Returns an array of Medicos objects with Agenda
     */
    public function medicosComAgendas($params = Array())
    {
        $qb = $this->createQueryBuilder('M');
        $qb->join('M.agenda','A');
        
        if(array_key_exists('cliente',$params) and  !empty($params['cliente'])){
            $qb->andWhere('M.cliente in(:clienteId)')
            ->setParameter('clienteId',$params['cliente']->getId());
        }

        $orModule = $qb->expr()->orx();
        if(array_key_exists('dataInicio',$params) and  !empty($params['dataInicio'])){
            $orModule->add(':dataInicio >= A.dataInicioAtendimento AND :dataInicio <= A.dataFimAtendimento ');
            $qb->setParameter('dataInicio',$params['dataInicio']);
        }
        
        if(array_key_exists('dataFim',$params) and  !empty($params['dataFim'])){
            $orModule->add(':dataFim >= A.dataInicioAtendimento AND :dataInicio <= A.dataFimAtendimento ');
            $qb->setParameter('dataFim',$params['dataFim']);
        }

        $qb->andWhere( $orModule);

        $qb->orderBy('A.id', 'ASC')
            ->setMaxResults(50)
        ;
        
        return $qb;
    }

    public function medicosComAgendasArrayResult($params = Array())
    {
        $qb = $this->medicosComAgendas($params);
        $qb->select('M, A');
        return $qb->getQuery()->getArrayResult();
    }
}