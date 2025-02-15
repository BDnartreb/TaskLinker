<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('employees', EntityType::class, [
                'class' => Employee::class,
                'query_builder' => function(EmployeeRepository $employee): QueryBuilder{
                    return $employee->createQueryBuilder('e')->orderBy('e.firstName', 'ASC');
                },
                'choice_label' => function($employee){
                    return $employee->getFirstname() . ' ' . $employee->getLastname();
                },
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,//permet préremplissage des champs du form
        ]);
    }
}
