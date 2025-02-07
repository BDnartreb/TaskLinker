<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Form\RegistrationType;
use App\Repository\ContractStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeeController extends AbstractController
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private ContractStatusRepository $contractStatusRepository,
    )
    {
    }

    #[Route('/employees', name: 'app_employees')]
    public function index(): Response
    {
        $employees = $this->employeeRepository->findAll();

        return $this->render('employee/employees.html.twig', [
            'controller_name' => 'EmployeeController',
            'employees' => $employees,
        ]);
    }

    #[Route('/employee/{id}', name: 'app_employee', requirements:['id' => '\d+'], methods: ['GET', 'POST'])]
    public function employee(?Employee $employee, Request $request, EntityManagerInterface $manager): Response
    {
        $employee ??= new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($employee);
            $manager->flush();

            return $this->redirectToRoute('app_employees');
        }

        return $this->render('employee/employee.html.twig', [
            'form' => $form,
            'employee' => $employee,
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $employee = new Employee();
        $cdi = $this->contractStatusRepository->findOneBy(['status' => 'CDI']);
        $employee->setContractStatus($cdi);
        $employee->setRecruitmentDate(new \DateTime());

        $form = $this->createForm(RegistrationType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $employee->setPassword($userPasswordHasher->hashPassword($employee, $plainPassword));
 
            //$entityManager->persist($employee);
            $entityManager->persist($employee);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('connection/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/employee/supprimer', name: 'app_delete_employee', requirements:['id' => '\d+'], methods: ['GET', 'POST'])]
    public function deleteEmployee(Request $request, EntityManagerInterface $manager): Response
    {
        $employeeId = $request->get('id');
        $employee = $this->employeeRepository->find($employeeId);
        $manager->remove($employee);
        $manager->flush();
        return $this->redirectToRoute('app_employees');
   }

}

/*
 #[IsGranted('ROLE_AJOUT_DE_LIVRE')]
    #[Route('/new', name: 'app_admin_author_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_admin_author_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(?Author $author, Request $request, EntityManagerInterface $manager): Response
    {
        if ($author) {
            $this->denyAccessUnlessGranted('ROLE_EDITION_DE_LIVRE');
        }

        $author ??= new Author();
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($author);
            $manager->flush();

            //return $this->redirectToRoute('app_admin_author_new');
            return $this->redirectToRoute('app_admin_author_show', ['id' => $author->getId()]);
        }

        return $this->render('admin/author/new.html.twig', [
            'form' => $form,
        ]);
        
    } */
