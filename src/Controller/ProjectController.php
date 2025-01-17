<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Form\ArchiveType;
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

        //$avatar = substr($project->getEmployees()->getFirstName(), 0, 1);

        return $this->render('project/project.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
            //'avatar' => $avatar,
        ]);
    }

    #[Route('project/ajouter' , name: 'app_add_project', methods: ['GET', 'POST'])]
    #[Route('project/{id}/edit' , name: 'app_edit_project', methods: ['GET', 'POST'])]
    public function addProject(?Project $project, Request $request, EntityManagerInterface $manager)
    {
        $project ??= new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        }



        return $this->render('project/addproject.html.twig', [
            'form' => $form,
            'title' => $project->getTitle(),

        ]);
    }

    #[Route('project/{id}/archive' , name: 'app_archive_project', methods: ['GET', 'POST'])]
    public function archiveProject(int $id, Request $request, EntityManagerInterface $manager)
    {
        $id = $request->get('id');
        $project = $this->projectRepository->find($id);
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('project/addproject.html.twig', [
            'form' => $form,
            'title' => $project->getTitle(),
        ]);
    }

}
