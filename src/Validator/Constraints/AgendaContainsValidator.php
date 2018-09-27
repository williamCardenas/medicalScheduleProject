<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use App\Entity\Agenda;

class AgendaContainsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        
    }

    /**
     * Valida se a data da $agenda não sobrepoe nenuma data existente em $datasExistentes
     * @param Agenda $agenda agenda a ser validada
     * @param Array $agendasExistentes agendas já cadastradas
     * @return Boolean
     */
    public function validaDataDisponivel(Agenda $agenda, Array $agendasExistentes){
        foreach( $agendasExistentes as $currentAgenda){
            if($agenda->getDataInicioAtendimento() >= $currentAgenda->getDataInicioAtendimento()
                && $agenda->getDataInicioAtendimento() <= $currentAgenda->getDataFimAtendimento()
            ){
                return false;
            }
            if($agenda->getDataFimAtendimento() >= $currentAgenda->getDataInicioAtendimento()
                && $agenda->getDataFimAtendimento() <= $currentAgenda->getDataFimAtendimento()
            ){
                return false;
            }
        }
        return true;
    }
}