<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class EmployeeController extends AbstractController
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
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
