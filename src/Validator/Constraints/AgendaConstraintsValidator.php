<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Agenda;
use App\Repository\AgendaRepository;

class AgendaConstraintsValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($agenda, Constraint $constraint)
    {
        $agendaRepository = $this->entityManager->getRepository(Agenda::class);
        $agendas = $agendaRepository->findByParams([
            'medico' => ['value' => $agenda->getMedico()->getId(), 'operator' => '='],
            'clinica' => ['value' => $agenda->getClinica()->getId(), 'operator' => '='],
            'id' => ['value' => $agenda->getId(), 'operator' => '!=']
        ]);
        $retornoValidacao = $this->validaDataDisponivel($agenda,$agendas);

        if(!$retornoValidacao){
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

    /**
     * Valida se a data da $agenda não sobrepoe nenuma data existente em $datasExistentes
     * @param Agenda $agenda agenda a ser validada
     * @param Array $agendasExistentes agendas já cadastradas
     * @return Boolean
     */
    public function validaDataDisponivel(Agenda $agenda, Array $agendasExistentes){
        foreach( $agendasExistentes as $currentAgenda){

            if($agenda->getDataInicioAtendimento() > $currentAgenda->getDataInicioAtendimento()
            && $agenda->getDataInicioAtendimento() < $currentAgenda->getDataFimAtendimento()
            && empty($agenda->getHorarioFimAtendimento()) 
            && empty($currentAgenda->getHorarioInicioAtendimento())
            && $agenda->getHorarioInicioAtendimento() >= $currentAgenda->getHorarioInicioAtendimento()
            && $agenda->getHorarioInicioAtendimento() <= $currentAgenda->getHorarioFimAtendimento()
            ){
                return false;
            }elseif(!empty($agenda->getHorarioFimAtendimento()) && !empty($currentAgenda->getHorarioInicioAtendimento())
            && $agenda->getHorarioInicioAtendimento() >= $currentAgenda->getHorarioInicioAtendimento()
            && $agenda->getHorarioInicioAtendimento() <= $currentAgenda->getHorarioFimAtendimento()
            ){
                return false;
            }

            if($agenda->getDataFimAtendimento() > $currentAgenda->getDataInicioAtendimento()
            && $agenda->getDataFimAtendimento() < $currentAgenda->getDataFimAtendimento()
            && empty($agenda->getHorarioFimAtendimento()) 
            && empty($currentAgenda->getHorarioInicioAtendimento())
            && $agenda->getHorarioFimAtendimento() >= $currentAgenda->getHorarioInicioAtendimento()
            && $agenda->getHorarioFimAtendimento() <= $currentAgenda->getHorarioFimAtendimento()
            ){
                return false;
            }elseif(!empty($agenda->getHorarioFimAtendimento()) && !empty($currentAgenda->getHorarioInicioAtendimento())
            && $agenda->getHorarioFimAtendimento() >= $currentAgenda->getHorarioInicioAtendimento()
            && $agenda->getHorarioFimAtendimento() <= $currentAgenda->getHorarioFimAtendimento()
            ){
                return false;
            }


            if($agenda->getDataInicioAtendimento() < $currentAgenda->getDataInicioAtendimento()
            && $agenda->getDataFimAtendimento() > $currentAgenda->getDataFimAtendimento()
            ){
                return false;
            }elseif($agenda->getHorarioInicioAtendimento() < $currentAgenda->getHorarioInicioAtendimento()
            && $agenda->getHorarioFimAtendimento() > $currentAgenda->getHorarioFimAtendimento()
            ){
                return false;
            }
        }
        return true;
    }
}