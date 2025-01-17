<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\ContractStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contractStatus', EntityType::class, [
                'class' => ContractStatus::class,
                'choice_label' => 'status',
            ])
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('recruitmentDate', null, [
                'widget' => 'single_text',
            ])
            /*->add('projects', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
