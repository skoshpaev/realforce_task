<?php

namespace App\Tests\Component;

use App\Component\SalaryValidator;
use App\DTO\EmployeeDTO;
use PHPUnit\Framework\TestCase;

class SalaryValidatorTest extends TestCase
{
    const TEST_ENTITIES = [
        'salaryByAge26' => '{"age": 26,"kids_number": 2,"has_company_car": false,"salary": 1000}',
        'salaryByAge50' => '{"age": 50,"kids_number": 2,"has_company_car": false,"salary": 1000}',
        'salaryByAge55' => '{"age": 55,"kids_number": 2,"has_company_car": false,"salary": 1000}',

        'salaryByKids0' => '{"age": 23,"kids_number": 0,"has_company_car": false,"salary": 1000}',
        'salaryByKids1' => '{"age": 23,"kids_number": 1,"has_company_car": false,"salary": 1000}',
        'salaryByKids2' => '{"age": 23,"kids_number": 2,"has_company_car": false,"salary": 1000}',
        'salaryByKids3' => '{"age": 23,"kids_number": 3,"has_company_car": false,"salary": 1000}',

        'salaryByCar0' => '{"age": 23,"kids_number": 0,"has_company_car": false,"salary": 1000}',
        'salaryByCar1' => '{"age": 23,"kids_number": 0,"has_company_car": true,"salary": 1000}',

        'salaryByTax' => '{"age": 23,"kids_number": 0,"has_company_car": true,"salary": 1000}',

        'salaryByEmployeeAlice' =>      '{"age": 26,"kids_number": 2,"has_company_car": false,"salary": 6000}',
        'salaryByEmployeeBob' =>        '{"age": 52,"kids_number": 0,"has_company_car": true,"salary": 4000}',
        'salaryByEmployeeCharlie' =>    '{"age": 36,"kids_number": 3,"has_company_car": true,"salary": 5000}',
        'salaryByEmployeeStan' =>       '{"age": 52,"kids_number": 3,"has_company_car": true,"salary": 10000}',
    ];

    private EmployeeDTO $employee;
    private SalaryValidator $salaryValidator;

    public function __construct()
    {
        $this->salaryValidator = new SalaryValidator();
        $this->employee = new EmployeeDTO();

        parent::__construct();
    }

    public function testCalculateSalaryByAge()
    {
        $this->employee->build(self::TEST_ENTITIES['salaryByAge26']);
        $this->assertEquals(1000, $this->salaryValidator->calculateSalaryByAge($this->getEmployee()->getAge(),$this->getEmployee()->getSalary()));

        $this->employee->build(self::TEST_ENTITIES['salaryByAge50']);
        $this->assertEquals(1000, $this->salaryValidator->calculateSalaryByAge($this->getEmployee()->getAge(),$this->getEmployee()->getSalary()));

        $this->employee->build(self::TEST_ENTITIES['salaryByAge55']);
        $this->assertEquals(1070, $this->salaryValidator->calculateSalaryByAge($this->getEmployee()->getAge(),$this->getEmployee()->getSalary()));
    }

    public function testCalculateTaxByKids()
    {
        $this->employee->build(self::TEST_ENTITIES['salaryByKids0']);
        $this->assertEquals($_ENV['COUNTRY_TAX'], $this->salaryValidator->calculateTaxByKids($this->getEmployee()->getKidsNumber()));

        $this->employee->build(self::TEST_ENTITIES['salaryByKids1']);
        $this->assertEquals($_ENV['COUNTRY_TAX'], $this->salaryValidator->calculateTaxByKids($this->getEmployee()->getKidsNumber()));

        $this->employee->build(self::TEST_ENTITIES['salaryByKids2']);
        $this->assertEquals($_ENV['COUNTRY_TAX'], $this->salaryValidator->calculateTaxByKids($this->getEmployee()->getKidsNumber()));

        $this->employee->build(self::TEST_ENTITIES['salaryByKids3']);
        $this->assertEquals($_ENV['COUNTRY_TAX'] - $_ENV['COUNTRY_TAX_DECREASE_FOR_KIDS'], $this->salaryValidator->calculateTaxByKids($this->getEmployee()->getKidsNumber()));
    }

    public function testCalculateSalaryByCompanyCar()
    {
        $this->employee->build(self::TEST_ENTITIES['salaryByCar0']);
        $this->assertEquals(1000, $this->salaryValidator->calculateSalaryByCompanyCar($this->getEmployee()->hasCompanyCar(),$this->getEmployee()->getSalary()));

        $this->employee->build(self::TEST_ENTITIES['salaryByCar1']);
        $this->assertEquals(500, $this->salaryValidator->calculateSalaryByCompanyCar($this->getEmployee()->hasCompanyCar(),$this->getEmployee()->getSalary()));
    }

    public function testCalculateSalaryByTax()
    {
        $this->employee->build(self::TEST_ENTITIES['salaryByTax']);
        $this->assertEquals(800, $this->salaryValidator->calculateSalaryByTax($this->getEmployee()->getSalary()));

        $this->setPrivateCountryTax($this->salaryValidator, 25);
        $this->employee->build(self::TEST_ENTITIES['salaryByTax']);
        $this->assertEquals(750, $this->salaryValidator->calculateSalaryByTax($this->getEmployee()->getSalary()));
    }

    public function testGetSalaryByEmployee()
    {
        $this->employee->build(self::TEST_ENTITIES['salaryByEmployeeAlice']);
        $this->assertEquals(4800.0, $this->salaryValidator->getSalaryByEmployee($this->getEmployee()));
        $this->setPrivateCountryTax($this->salaryValidator, 20);

        $this->employee->build(self::TEST_ENTITIES['salaryByEmployeeBob']);
        $this->assertEquals(3024.0, $this->salaryValidator->getSalaryByEmployee($this->getEmployee()));
        $this->setPrivateCountryTax($this->salaryValidator, 20);

        $this->employee->build(self::TEST_ENTITIES['salaryByEmployeeCharlie']);
        $this->assertEquals(3690.0, $this->salaryValidator->getSalaryByEmployee($this->getEmployee()));
        $this->setPrivateCountryTax($this->salaryValidator, 20);

        $this->employee->build(self::TEST_ENTITIES['salaryByEmployeeStan']);
        $this->assertEquals(8364.0, $this->salaryValidator->getSalaryByEmployee($this->getEmployee()));
        $this->setPrivateCountryTax($this->salaryValidator, 20);
    }

    /**
     * @return EmployeeDTO
     */
    public function getEmployee(): EmployeeDTO
    {
        return $this->employee;
    }

    private function setPrivateCountryTax(SalaryValidator $salaryValidator, $value)
    {
        $closure = function ($value) {
            $this->countryTax = $value;
        };

        $closure->call($salaryValidator, $value);
    }

}