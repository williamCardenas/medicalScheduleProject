<?php

namespace App\Repository;

use App\Entity\Paciente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Paciente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paciente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paciente[]    findAll()
 * @method Paciente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PacienteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
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
        $qb->orderBy('P.nome', 'ASC');

        return $qb;
    }

    public function searchResult($params)
    {
        $qb = $this->search($params);
        return $qb->getQuery()->getResult();
    }
}
