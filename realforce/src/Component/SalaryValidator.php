<?php

namespace App\Component;

use App\DTO\EmployeeDTO;

class SalaryValidator
{
    private int $countryTax;
    private int $ageLimit;
    private int $kidsLimit;
    private int $deductForCar;

    public function __construct()
    {
        $this->countryTax = $_ENV['COUNTRY_TAX'];
        $this->ageLimit = $_ENV['AGE_LIMIT'];
        $this->kidsLimit = $_ENV['KIDS_LIMIT'];
        $this->deductForCar = $_ENV['DEDUCT_FOR_CAR'];
    }

    public function getSalaryByEmployee(EmployeeDTO $employee): float
    {
        $salary = $employee->getSalary();
        $salary = $this->calculateSalaryByAge($employee->getAge(), $salary);
        $this->setCountryTax($this->calculateTaxByKids($employee->getKidsNumber()));
        $salary = $this->calculateSalaryByCompanyCar($employee->hasCompanyCar(), $salary);

        return $this->calculateSalaryByTax($salary);
    }

    /**
     * @return int|mixed
     */
    public function getAgeLimit()
    {
        return $this->ageLimit;
    }

    /**
     * @return int|mixed
     */
    public function getKidsLimit()
    {
        return $this->kidsLimit;
    }

    /**
     * @return int
     */
    public function getCountryTax(): int
    {
        return $this->countryTax;
    }

    /**
     * @param int $countryTax
     */
    public function setCountryTax(int $countryTax): void
    {
        $this->countryTax = $countryTax;
    }

    public function calculateSalaryByAge(int $age, $salary): float
    {
        if ($age > $this->ageLimit) {
            return $salary + round($salary * 0.07);
        }

        return $salary;
    }

    public function calculateTaxByKids(int $getKidsNumber): int
    {
        if ($getKidsNumber > $this->getKidsLimit()) {
            return $this->getCountryTax() - $_ENV['COUNTRY_TAX_DECREASE_FOR_KIDS'];
        }

        return $this->getCountryTax();
    }

    public function calculateSalaryByCompanyCar(bool $hasCompanyCar, float $salary): float
    {
        if (true === $hasCompanyCar) {
            return $salary - $this->deductForCar;
        }

        return $salary;
    }

    public function calculateSalaryByTax(float $salary): float
    {
        return $salary - ($salary * ($this->getCountryTax() * 0.01));
    }
}