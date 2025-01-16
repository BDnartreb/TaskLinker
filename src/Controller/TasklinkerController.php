<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProjectRepository;

class TasklinkerController extends AbstractController
{
    public function __construct (
        private ProjectRepository $projectRepository,
    )
    {
    }

    #[Route('/tasklinker', name: 'app_home')]
    public function index(): Response
    {
        $projects = $this->projectRepository->findAll();

        return $this->render('tasklinker/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
