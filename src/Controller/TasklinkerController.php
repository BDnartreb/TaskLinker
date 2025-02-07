<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProjectRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TasklinkerController extends AbstractController
{
    public function __construct (
        private ProjectRepository $projectRepository,
        private AuthenticationUtils $authenticationUtils,
    )
    {
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/tasklinker', name: 'app_home')]
    public function index(): Response
    {
        $projects = $this->projectRepository->findAll();
        $user = $this->getUser();

        return $this->render('tasklinker/index.html.twig', [
            'projects' => $projects,
            'user' => $user,
        ]);
    }

    #[Route('/welcome', name: 'app_welcome')]
    public function welcome(): Response
    {
        return $this->render('connection/welcome.html.twig');
    }
}
