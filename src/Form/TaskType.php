<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\StatusTask;
use App\Entity\Task;
use App\Repository\EmployeeRepository;
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
        $projectId = $options['projectId']; //gets options_resolver param of the buildForm
        $builder
            ->add('title', TextType::class, ['label' => 'Titre de la tâche'])
            ->add('description', TextareaType::class)
            ->add('deadline')
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'query_builder' => function (EmployeeRepository $er) use ($projectId) {
                    return $er->createQueryBuilder('e')
                        ->join('e.projects', 'p')
                        ->where('p.id = :projectId')
                        ->setParameter('projectId', $projectId)
                    ;
                },
                'choice_label' => function($employee){
                    return $employee->getFirstname() . ' ' . $employee->getLastname();
                },
            ])
            ->add('status', EntityType::class, [
                'class' => StatusTask::class,
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
            'projectId' => null // options_resolver param used by the buildForm (param coming for TaskController)
        ]);
    }
}
