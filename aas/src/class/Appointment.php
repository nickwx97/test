<!-- getter setter for Appointment class -->

<?php
include_once "database.php";

class Appointment
{
    // Properties
    public $appointment_id;
    public $appointment_date_time;
    public $venue;
    public $timeslot;
    public $user_id;
    public $employee_id;

    // Methods
    public function setAppointmentID($appointment_id)
    {
        $this->appointment_id = $appointment_id;
    }

    public function getAppointmentID()
    {
        return $this->appointment_id;
    }

    public function setAppointmentDate($appointment_date_time)
    {
        $this->appointment_date_time = $appointment_date_time;
    }

    public function getAppointmentDate()
    {
        return $this->appointment_date_time;
    }

    public function setVenue($venue)
    {
        $this->venue = $venue;
    }

    public function getVenue()
    {
        return $this->venue;
    }

    public function setTImeslot($timeslot)
    {
        $this->timeslot = $timeslot;
    }

    public function getTimeslot()
    {
        return $this->timeslot;
    }

    public function getUserID()
    {
        return $this->user_id;
    }

    public function setUserID($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setEmployeeID($employee_id)
    {
        $this->employee_id = $employee_id;
    }

    public function getEmployeeID()
    {
        return $this->employee_id;
    }
}