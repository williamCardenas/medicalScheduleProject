<?php

namespace App\Form;

use App\Entity\Agenda;
use App\Entity\Clinica;
use App\Entity\Medico;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class AgendaType extends AbstractType
{
    private $user;

    public function __construct(Security $security){
        $this->user = $security->getUser();
        // parent::__construct();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                'query_builder' => function (EntityRepository $er) {
                    
                    return $er->createQueryBuilder('C')
                        ->andWhere('C.cliente in(:clienteId)')
                        ->setParameter('clienteId',$this->user->getCliente())
                        ->orderBy('C.nome', 'ASC');
                },
                'choice_label' => 'nome',
                'placeholder' => 'Selecione',
            ))
            ->add('medico', EntityType::class, array(
                'class' => Medico::class,
                'query_builder' => function (EntityRepository $er) {
                    
                    return $er->createQueryBuilder('M')
                        ->andWhere('M.cliente in(:clienteId)')
                        ->setParameter('clienteId',$this->user->getCliente())
                        ->orderBy('M.nome', 'ASC');
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
        ]);
    }
}
