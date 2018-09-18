<?php

namespace App\Form;

use App\Entity\Agenda;
use App\Entity\Clinica;
use App\Entity\Medico;
use App\Repository\ClinicaRepository;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class AgendaType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('dataInicioAtendimento', DateType::class , array(
                'widget' => 'single_text',
            ))
            ->add('dataFimAtendimento', DateType::class , array(
                'widget' => 'single_text',
            ))
            ->add('horarioInicioAtendimento', TimeType::class , array(
                'widget' => 'single_text',
            ))
            ->add('horarioFimAtendimento', TimeType::class , array(
                'widget' => 'single_text',
            ))
            ->add('fimDeSemana')
            ->add('clinica', EntityType::class, array(
                'class' => Clinica::class,
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('C')
                        ->andWhere('C.cliente in(:clienteId)')
                        ->setParameter('clienteId',$user->getCliente()->getId())
                        ->orderBy('C.nome', 'ASC');
                },
                'choice_label' => 'nome',
                'placeholder' => 'Selecione',
                ))
            ->add('medico', EntityType::class, array(
                'class' => Medico::class,
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('C')
                        ->andWhere('C.cliente in(:clienteId)')
                        ->setParameter('clienteId',$user->getCliente()->getId())
                        ->orderBy('C.nome', 'ASC');
                },
                'choice_label' => 'nome',
                'placeholder' => 'Selecione',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agenda::class,
            'user' => null
        ]);
    }
}
