<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Entity\Project;

class TaskController extends AbstractController
{
    public function __construct (
        private ProjectRepository $projectRepository,
        private TaskRepository $taskRepository,
    )
    {
    }

    #[Route('/task', name: 'app_task')]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    #[Route('/tache/{id}/ajouter', name: 'app_add_task', methods: ['GET', 'POST'])]
    public function addTask(Project $project, Request $request, EntityManagerInterface $manager): Response
    {
        $task ??= new Task();
        $task->setProject($project);
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('app_project', ['id' => $project->getId()]);
        }

        return $this->render('task/task.html.twig', [
            'form' => $form,
            'title' => $task->getTitle(),
        ]);
    }

    #[Route('/tache/{id}/editer', name: 'app_edit_task', methods: ['GET', 'POST'])]
    public function editTask(Task $task, Request $request, EntityManagerInterface $manager): Response
    {
        $task ??= new Task();
        $form = $this->createForm(TaskType::class, $task,
        [
            'projectId' => $task->getProject()->getId()
        ]);
        // projectId defined as options_resolver of createForm, as param for TaskType.php file

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('app_project', ['id' => $task->getProject()->getId()]);
        }

       /* $firstName = $task->getEmployee()->getFirstName();
        $l = $task->setFirstLetter($firstName);
        $firstLetter = $task->getFirstLetter($l);
        var_dump($firstLetter);*/

        return $this->render('task/task.html.twig', [
            'form' => $form,
            'task' => $task,
            'title' => $task->getTitle(),
         //   'firstLetter' => $firstLetter,
        ]);
    }

    #[Route('/tache/supprimer', name: 'app_delete_task', methods: ['GET', 'POST'])]
    public function deleteTask(Request $request, EntityManagerInterface $manager): Response
    {
        $taskId = $request->get('id');
        $task = $this->taskRepository->find($taskId);
        $manager->remove($task);
        $manager->flush();
        return $this->redirectToRoute('app_project', ['id' => $task->getProject()->getId()]);
    }




   /* #[Route('/task/delete', name: 'app_delete_task', methods: ['GET', 'POST'])]
    public function deleteTask(?Task $task, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->remove($form);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        }        
        
        return $this->render('project/addproject.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }*/

}
