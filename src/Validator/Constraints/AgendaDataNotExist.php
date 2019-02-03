<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AgendaDataNotExist extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'mensagem.erro.validacao.agendaData.jaExiste';

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}
