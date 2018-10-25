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
        $qb = $this->createQueryBuilder('a');
        
        if(array_key_exists('id',$params) && !empty($params['id'])){
            $qb->andWhere('a.id '.$params['id']['operator'].' :id')
            ->setParameter('id', $params['id']['value']);
        }

        if(array_key_exists('medico',$params) && !empty($params['medico'])){
            $qb->andWhere('a.medico '.$params['medico']['operator'].' :medico')
            ->setParameter('medico', $params['medico']['value']);
        }

        if(array_key_exists('clinica',$params) && !empty($params['clinica'])){
            $qb->andWhere('a.clinica '.$params['clinica']['operator'].' :clinica')
            ->setParameter('clinica', $params['clinica']['value']);
        }

        if(array_key_exists('data',$params) and  !empty($params['data'])){
            $orModule = $qb->expr()->orx();

            $orModule->add(':data >= A.dataInicioAtendimento AND :data <= A.dataFimAtendimento ');
            $qb->setParameter('data',$params['data']);

            $qb->andWhere( $orModule);
        }

        $qb->orderBy('a.id', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
        ;
        
        return $qb->getQuery()->getResult();
    }

    /**
     * @param Array $param array with filters
     * @param Array $horarios array with horarios registred
     * 
     * @return Array[] Returns an array with hours
     */
    public function horariosDisponiveisArray($param,$horariosMarcados = array()){
        if(!array_key_exists('data',$params) && !empty($params['data'])){
            throw new \Exception('paramter data not found');
        }

        if(!array_key_exists('medico',$params) && !empty($params['medico'])){
            throw new \Exception('paramter medico not found');
        }
]
        $agendas = $this->findByParams($param);
        $horarios = [];

        foreach($agendas as $agenda){
            $config = $agenda->getAgendaConfig();

            $horario = $agenda->getHorarioInicioAtendimento();
            $intervalo = new DateInterval('PT'.$config->getDuracaoConsulta().'M')

            for($horario; $horario <= $agenda->getHorarioFimAtendimento(); $horario->add($intervalo)){
                foreach($horariosMarcados as $horarioMarcado){
                    $horarioConsulta = $horarioMarcado->getHorarioConsulta();
                    if($horarioConsulta->format('H:i:s') != $horario->format('H:i:s') && !array_search($horario->format('H:i:s'))){
                        array_push($horarios,$horario->format('H:i:s'))
                    }
                    if($horarioConsulta > $horario){
                        break;
                    }
                }
            }
        }

        return $horario;

    }

}
