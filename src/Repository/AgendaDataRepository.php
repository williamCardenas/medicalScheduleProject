<?php

namespace App\Repository;

use App\Entity\AgendaData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AgendaData|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgendaData|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgendaData[]    findAll()
 * @method AgendaData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AgendaData::class);
    }

    /**
     * @return Agenda[] Returns an array of Agenda objects
     */
    public function findByParams(Array $params)
    {
        $qb = $this->createQueryBuilder('AD');
        
        if(array_key_exists('id',$params) && !empty($params['id'])){
            $qb->andWhere('AD.id '.$params['id']['operator'].' :id')
            ->setParameter('id', $params['id']['value']);
        }

        if(array_key_exists('dataConsulta',$params) and  !empty($params['dataConsulta'])){
            $orModule = $qb->expr()->orx();

            $orModule->add('AD.dataConsulta = :dataConsulta');
            $qb->setParameter('dataConsulta',$params['dataConsulta']);

            $qb->andWhere( $orModule);
        }

        $qb->orderBy('AD.dataConsulta, AD.id', 'ASC');
        
        return $qb->getQuery()->getResult();
    }
}
