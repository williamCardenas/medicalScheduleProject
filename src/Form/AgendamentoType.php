<?php

namespace App\Form;

use App\Entity\Agenda;
use App\Entity\AgendaData;
use App\Entity\Paciente;
use App\Entity\Medico;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class AgendamentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('paciente', EntityType::class , array(
                'class' => Paciente::class,
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('P')
                        ->andWhere('P.cliente in(:clienteId)')
                        ->setParameter('clienteId',$user->getCliente()->getId())
                        ->orderBy('P.nome', 'ASC');
                },
                'choice_label' => 'nome',
                'placeholder' => 'Selecione',
            ))
            ->add('medico', HiddenType::class , array(
                'mapped' => false,
            ))
            ->add('dataConsulta', DateTimeType::class , array(
                
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AgendaData::class,
            'user' => null
        ]);
    }
}
