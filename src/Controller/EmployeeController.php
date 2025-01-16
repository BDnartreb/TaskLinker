<?php

namespace App\Controller;

use App\Form\EmployeeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EmployeeRepository;

class EmployeeController extends AbstractController
{
    public function __construct(private EmployeeRepository $employeeRepository)
    {
    }

    #[Route('/employee', name: 'app_employees')]
    public function index(?int $id): Response
    {
        $employees = $this->employeeRepository->findAll();

        return $this->render('employee/employees.html.twig', [
            'controller_name' => 'EmployeeController',
            'employees' => $employees,
        ]);
    }

    #[Route('/employee/{id}', name: 'app_employee', requirements:['id' => '\d+'], methods: ['GET'])]
    public function employee(?int $id): Response
    {
        $employee = $this->employeeRepository->find($id);
        $form = $this->createForm(EmployeeType::class, $employee);
        return $this->render('employee/employee.html.twig', [
            'form' => $form,
            'employee' => $employee,
        ]);
    }
}
