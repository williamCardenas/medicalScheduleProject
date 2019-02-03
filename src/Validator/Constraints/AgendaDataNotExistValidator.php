<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\AgendaData;
use App\Repository\AgendaDataRepository;

class AgendaDataNotExistValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        $agendaDataRepository = $this->entityManager->getRepository(AgendaData::class);

        $agendamentoExiste = $agendaDataRepository->findByParams([
            'dataHoraConsulta'=>[
                'operator' => '=',
                'value' => $value->format('Y-m-d H+i+s')
            ]
        ]);

        if(count($agendamentoExiste) > 0){
            $this->context->buildViolation($constraint->message)
            ->addViolation();
        }
    }
}
