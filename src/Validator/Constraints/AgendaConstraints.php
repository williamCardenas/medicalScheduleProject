<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class AgendaConstraints extends Constraint
{
    public $message = 'mensagem.erro.validacao.agenda.jaExiste';
    
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}