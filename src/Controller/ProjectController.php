<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Zenstruck\Foundry\Persistence\persist;

class ProjectController extends AbstractController
{
    public function __construct (
        private ProjectRepository $projectRepository,
        private TaskRepository $taskRepository,
    )
    {
    }

    #[Route('/project/{id}', name: 'app_project', requirements:['id' => '\d+'], methods: ['GET'])]
    public function project(int $id): Response
    {
        $project = $this->projectRepository->find($id);
        $tasks = $this->taskRepository->findBy(['project' => $id]);

        if(!$project){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('project/project.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }

    #[Route('project/ajouter' , name: 'app_add_project', methods: ['GET', 'POST'])]
    #[Route('project/{id}/edit' , name: 'app_edit_project', methods: ['GET', 'POST'])]
    public function addProject(?Project $project, Request $request, EntityManagerInterface $manager)
    {
        $id = $request->get('id');
        $project ??= new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('app_project', ['id' => $id]);
        }

        return $this->render('project/addproject.html.twig', [
            'form' => $form,
            'title' => $project->getTitle(),
        ]);
    }

    #[Route('/tache/{id}/ajouter', name: 'app_add_task', methods: ['GET', 'POST'])]
    #[Route('/tache/{id}/editer', name: 'app_edit_task', methods: ['GET', 'POST'])]
    public function addTask(?Task $task, Request $request, EntityManagerInterface $manager): Response
    {
        $id = $request->get('id'); //get the project id to route to this project after a task adding
        $task ??= new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('app_project', ['id' => $id]);
        }

        return $this->render('task/task.html.twig', [
            'form' => $form,
            'title' => $task->getTitle(),
        ]);
    }


}
