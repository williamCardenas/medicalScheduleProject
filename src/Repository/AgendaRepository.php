<?php

namespace App\Repository;

use App\Entity\Agenda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use DateTime;
use DateInterval;

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
        $qb = $this->createQueryBuilder('A');
        
        if(array_key_exists('id',$params) && !empty($params['id'])){
            $qb->andWhere('A.id '.$params['id']['operator'].' :id')
            ->setParameter('id', $params['id']['value']);
        }

        if(array_key_exists('medico',$params) && !empty($params['medico'])){
            $qb->andWhere('A.medico '.$params['medico']['operator'].' :medico')
            ->setParameter('medico', $params['medico']['value']);
        }

        if(array_key_exists('clinica',$params) && !empty($params['clinica'])){
            $qb->andWhere('A.clinica '.$params['clinica']['operator'].' :clinica')
            ->setParameter('clinica', $params['clinica']['value']);
        }

        if(array_key_exists('data',$params) and  !empty($params['data'])){
            $orModule = $qb->expr()->orx();

            $orModule->add(':data >= A.dataInicioAtendimento AND :data <= A.dataFimAtendimento ');
            $qb->setParameter('data',$params['data']);

            if(array_key_exists('hora',$params) and  !empty($params['hora'])){
                $orModule->add(':hora >= A.horarioInicioAtendimento AND :hora <= A.horarioFimAtendimento ');
                $qb->setParameter('hora',$params['hora']);
            }
    

            $qb->andWhere( $orModule);
        }

       
        $qb->orderBy('A.id', 'ASC')
            ->setMaxResults(50)
        ;
        
        return $qb->getQuery()->getResult();
    }

}
