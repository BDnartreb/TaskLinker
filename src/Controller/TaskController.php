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

class TaskController extends AbstractController
{
    public function __construct (
        private ProjectRepository $projectRepository,
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
    #[Route('/tache/{id}/editer', name: 'app_edit_task', methods: ['GET', 'POST'])]
    public function addTask(?Task $task, ?int $projectId, Request $request, EntityManagerInterface $manager): Response
    {
       /*if ($task) {
            $projectId = $task->getProject()->getId(); //get the project id to route to this project after a task adding
        } else {
            $projectId = $request->get('id');
        }*/
        
        $task ??= new Task();
        $project = $this->projectRepository->find($projectId);
        $task->setProject($project);
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('app_project', ['id' => $projectId]);
        }

        return $this->render('task/task.html.twig', [
            'form' => $form,
            'title' => $task->getTitle(),
        ]);
    }

    #[Route('/task/delete', name: 'app_delete_task', methods: ['GET', 'POST'])]
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
    }

}
