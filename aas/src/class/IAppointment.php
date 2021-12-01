<?php
// session_start();
include_once "database.php";

class IAppointment
{
    public $apptTable;
    public $dbObj;
    public $con;

    public function __construct()
    {
        $this->apptTable = "appointment";
        $this->dbObj = new Database();
        $this->con = $this->dbObj->connection();
    }

    // Fetch listing records for appointment
    public function displayApptData()
    {
        $role = $_SESSION['role'];
        //check session role
        if ($role == "guest"){
            $user_id = $_SESSION['user_id'];
            $query = $this->con->prepare("SELECT * FROM appointment inner join employee on appointment.employee_id=employee.employee_id inner join users on employee.user_id=users.user_id WHERE appointment.user_id=?");
            $query->bind_param("i", $user_id);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            } else {
                echo "No found records";
            }
        }
        elseif ($role == "employee"){
            $emp_id = $_SESSION['employee_id'];
            $query = $this->con->prepare("SELECT * FROM appointment inner join employee on appointment.employee_id=employee.employee_id inner join users on employee.user_id=users.user_id WHERE appointment.employee_id=?");
            $query->bind_param("i", $emp_id);
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows > 0) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            } else {
                echo "No found records";
            }
        }
    }

    // Fetch single data for edit from appt table
    public function displayApptById($appointment_id)
    {
        $sql = $this->con->prepare("SELECT * FROM appointment WHERE appointment_id = ?");
        $sql->bind_param("i", $appointment_id);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            echo "Record not found";
        }
        $sql->close();
    }

    // Fetch single data
    public function displayAccountById($employee_id)
    {
        $sql = $this->con->prepare("SELECT * FROM employee WHERE employee_id = ?");

        $sql->bind_param("i", $employee_id);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            echo "Record not found";
        }
        $sql->close();
    }

    // view analy
    public function viewAnalytics()
    {
        $query = "SELECT  appointment_id, appointment_date_time, count(*) FROM appointment
        WHERE appointment_date_time BETWEEN CURDATE() - INTERVAL 6 MONTH AND CURDATE() GROUP BY
	    appointment_date_time";

        $result = $this->con->query($query);
        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            echo "No found records";
        }
        $sql->close();
    }
}