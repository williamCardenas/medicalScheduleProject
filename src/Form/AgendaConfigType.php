<?php

namespace App\Form;

use App\Entity\AgendaConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use NumberFormatter;

class AgendaConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('valorConsulta', MoneyType::class, array(
                'divisor' => 100,
                'currency' => 'BRL'
            ))
            ->add('duracaoConsulta')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AgendaConfig::class,
        ]);
    }
}
