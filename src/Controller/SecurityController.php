<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProjectRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
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

        return $this->render('project/projects.html.twig', [
            'projects' => $projects,
            'user' => $user,
        ]);
    }

    #[Route('/welcome', name: 'app_welcome')]
    public function welcome(): Response
    {
        return $this->render('connection/welcome.html.twig');
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
