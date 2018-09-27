<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AgendaContains extends Constraint
{
    public $message = '';
    
    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}