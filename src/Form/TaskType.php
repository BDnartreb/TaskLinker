<?php

namespace App\Form;

use App\Entity\employee;
use App\Entity\project;
use App\Entity\StatusTask;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre de la tâche'])
            ->add('description', TextareaType::class)
            ->add('deadline', null, [
                'widget' => 'single_text',
            ])

            /*->add('project', EntityType::class, [
                'class' => project::class,
                'choice_label' => 'id',
            ])*/

            ->add('employee', EntityType::class, [
                'class' => employee::class,
                'choice_label' => function($employee){
                    return $employee->getFirstname() . ' ' . $employee->getLastname();
                },
            ])
            ->add('status', EntityType::class, [
                'class' => StatusTask::class,
                //'choice_label' => 'status',
                'choice_label' => function($task){
                    return $task->getStatus();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
