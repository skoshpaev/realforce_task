<?php

namespace App\Controller;

use App\Component\SalaryValidator;
use App\DTO\EmployeeDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{
    private SalaryValidator $salaryValidator;

    public function __construct(SalaryValidator $salaryValidator)
    {
        $this->salaryValidator = $salaryValidator;
    }

    /**
     * @Route("/", name="calculator", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function calculateSalary(Request $request): Response
    {
        $employee = new EmployeeDTO();
        if (false === $employee->build($request->getContent())) {
            return new JsonResponse(['Something went wrong. Please check your employee\'s data']);
        } else {
            $resultSalary = $this->salaryValidator->getSalaryByEmployee($employee);
            return new JsonResponse(['salary' => $resultSalary]);
        }
    }
}
