<?php

namespace App\DTO;

use Exception;

/**
 * Class EmployeeDTO - is Data transfer class for making an object from json
 * @package App\DTO
 */
class EmployeeDTO
{
    private int $age;
    private int $kidsNumber;
    private bool $hasCompanyCar;
    private float $salary;

    /**
     * The main method that builds all data from json into an object
     *
     * @param string $postBody
     * @return bool
     */
    public function build(string $postBody): bool
    {
        $array = json_decode($postBody, true);

        try {
            $this->age = $array['age'];
            $this->kidsNumber = $array['kids_number'];
            $this->hasCompanyCar = $array['has_company_car'];
            $this->salary = $array['salary'];
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @return int
     */
    public function getKidsNumber(): int
    {
        return $this->kidsNumber;
    }

    /**
     * @return bool
     */
    public function hasCompanyCar(): bool
    {
        return $this->hasCompanyCar;
    }

    /**
     * @return float
     */
    public function getSalary(): float
    {
        return $this->salary;
    }
}