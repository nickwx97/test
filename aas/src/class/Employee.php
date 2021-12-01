<!-- getter setter for Employee class -->

<?php
include_once "database.php";

class Employee
{
    // Properties
    public $employee_id;
    public $job_title;
    public $years_exp;
    public $specialisation;
    public $linkedin;
    public $cases_record;
    public $user_id;

    // Methods
    public function getEmployeeID()
    {
        return $this->employee_id;
    }

    public function setEmployeeID($employee_id)
    {
        $this->employee_id = $employee_id;
    }

    public function getJobTitle()
    {
        return $this->job_title;
    }

    public function setJobTitle($job_title)
    {
        $this->job_title = $job_title;
    }

    public function getYearsExp()
    {
        return $this->years_exp;
    }

    public function setYearsExp($years_exp)
    {
        $this->years_exp = $years_exp;
    }

    public function getSpecialisation()
    {
        return $this->specialisation;
    }

    public function setSpecialisation($specialisation)
    {
        $this->specialisation = $specialisation;
    }

    public function getLinkedin()
    {
        return $this->linkedin;
    }

    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
    }

    public function getCasesRecord()
    {
        return $this->cases_record;
    }

    public function setCasesRecord($cases_record)
    {
        $this->cases_record = $cases_record;
    }

    public function getUserID()
    {
        return $this->user_id;
    }

    public function setUserID($user_id)
    {
        $this->user_id = $user_id;
    }

}