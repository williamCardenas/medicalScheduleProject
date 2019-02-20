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
        $qb->join('AD.agenda','A');
        $qb->join('A.clinica','C');

        if(array_key_exists('id',$params) && !empty($params['id'])){
            $qb->andWhere('AD.id '.$params['id']['operator'].' :id')
            ->setParameter('id', $params['id']['value']);
        }

        if(array_key_exists('dataConsulta',$params) and  !empty($params['dataConsulta'])){
            $dataQuery = $qb->expr()->gte('AD.dataConsulta', "'{$params['dataConsulta']} 00:00:00'");
            $qb->andWhere($dataQuery);
            $dataQuery = $qb->expr()->lte('AD.dataConsulta', "'{$params['dataConsulta']} 23:59:59'");
            $qb->andWhere($dataQuery);
        }

        if(array_key_exists('dataHoraConsulta',$params) and  !empty($params['dataHoraConsulta'])){
            $qb->andWhere('AD.dataConsulta '.$params['dataHoraConsulta']['operator'].' :dataHoraConsulta')
            ->setParameter('dataHoraConsulta', $params['dataHoraConsulta']['value']);
        }

        if(array_key_exists('medico',$params) && !empty($params['medico'])){
            $qb->andWhere('A.medico '.$params['medico']['operator'].' :medico')
            ->setParameter('medico', $params['medico']['value']);
        }

        if(array_key_exists('clinica',$params) && !empty($params['clinica'])){
            $qb->andWhere('A.clinica '.$params['clinica']['operator'].' :clinica')
            ->setParameter('clinica', $params['clinica']['value']);
        }

        $qb->orderBy('AD.dataConsulta, AD.id', 'ASC');
        
        return $qb->getQuery()->getResult();
    }

    private function getQueryByDate($dataInicio,$dataFim){
        $qb = $this->createQueryBuilder('AD');
        $qb->join('AD.agenda','A');
        $qb->join('A.clinica','C');
        $qb->join('A.medico','M');
        $qb->join('A.agendaConfig','AG');
        
        $dataQuery = $qb->expr()->gte('AD.dataConsulta', "'{$dataInicio} 00:00:00'");
        $qb->andWhere($dataQuery);
        $dataQuery = $qb->expr()->lte('AD.dataConsulta', "'{$dataFim} 23:59:59'");
        $qb->andWhere($dataQuery);
    
        return $qb;
    }

    public function getAgendamentoByDate($dataInicio,$dataFim, Array $params){
        $qb = $this->getQueryByDate($dataInicio,$dataFim);
        $qb->select([
            'AD as agendaData',
            'M.nome as MedicoNome', 
            'M.id as MedicoId', 
            'M.corAgenda as corAgenda',
            'AG.duracaoConsulta as duracaoConsulta'
            ]);

        if(array_key_exists('clinica',$params) && !empty($params['clinica'])){
            $qb->andWhere('A.clinica '.$params['clinica']['operator'].' :clinica')
            ->setParameter('clinica', $params['clinica']['value']);
        }

        if(array_key_exists('cliente',$params) and  !empty($params['cliente'])){
            $qb->andWhere('C.cliente = :clienteId')
            ->setParameter('clienteId',$params['cliente']->getId());
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function getAgendamentoCountByDate($dataInicio,$dataFim){
        $qb = $this->getQueryByDate($dataInicio,$dataFim);

        $qb->select(['SUBSTRING(AD.dataConsulta,1,10) as data, count(AD.dataConsulta) as count']);
        $qb->groupBy('data');

        return $qb->getQuery()->getResult();
    }
}
