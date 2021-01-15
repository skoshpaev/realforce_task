<?php

namespace App\Component;

use App\DTO\EmployeeDTO;

class SalaryValidator
{
    private int $countryTax;
    private int $ageLimit;
    private int $kidsLimit;
    private int $deductForCar;

    /**
     * SalaryValidator constructor.
     */
    public function __construct()
    {
        $this->countryTax = $_ENV['COUNTRY_TAX'];
        $this->ageLimit = $_ENV['AGE_LIMIT'];
        $this->kidsLimit = $_ENV['KIDS_LIMIT'];
        $this->deductForCar = $_ENV['DEDUCT_FOR_CAR'];
    }

    /**
     * Common method for calculating final salary after all
     *
     * @param EmployeeDTO $employee
     * @return float
     */
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

    /**
     * The method calculates salary by age limit
     *
     * @param int $age
     * @param $salary
     * @return float
     */
    public function calculateSalaryByAge(int $age, $salary): float
    {
        if ($age > $this->ageLimit) {
            return $salary + round($salary * 0.07);
        }

        return $salary;
    }

    /**
     * The method calculates salary by kids
     *
     * @param int $getKidsNumber
     * @return int
     */
    public function calculateTaxByKids(int $getKidsNumber): int
    {
        if ($getKidsNumber > $this->getKidsLimit()) {
            return $this->getCountryTax() - $_ENV['COUNTRY_TAX_DECREASE_FOR_KIDS'];
        }

        return $this->getCountryTax();
    }

    /**
     * The method calculates salary by having company's car
     *
     * @param bool $hasCompanyCar
     * @param float $salary
     * @return float
     */
    public function calculateSalaryByCompanyCar(bool $hasCompanyCar, float $salary): float
    {
        if (true === $hasCompanyCar) {
            return $salary - $this->deductForCar;
        }

        return $salary;
    }

    /**
     * The method calculates salary by Tax
     *
     * @param float $salary
     * @return float
     */
    public function calculateSalaryByTax(float $salary): float
    {
        return $salary - ($salary * ($this->getCountryTax() * 0.01));
    }
}