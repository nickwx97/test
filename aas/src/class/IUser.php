<?php
// session_start();
include_once "database.php";

class IUser
{
    public $userTable;
    public $dbObj;
    public $con;

    public function __construct()
    {
        $this->userTable = "users";
        $this->dbObj = new Database();
        $this->con = $this->dbObj->connection();
    }

    ///    ****************************
    ///    ****************************
    ///    **** CRUD for Employee***
    ///    ****************************
    ///    ****************************

    // Fetch single data to edit from users table
    public function displayaRecordById($user_id)
    {
        $sql = $this->con->prepare("SELECT * FROM users WHERE user_id = ?");
        $sql->bind_param("i", $user_id);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            echo "Record not found";
        }
    }

     // employee table
    public function displayEmployeeRecordById($user_id)
    {
        $sql = $this->con->prepare("SELECT * FROM users u, employee e where u.user_id = e.user_id AND  u.user_id = ?");
        $sql->bind_param("i", $user_id);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            echo "Record not found";
        }
    }

    ///    ****************************
    ///    ****************************
    ///    **** CRUD for Admin ***
    ///    ****************************
    ///    ****************************

    //SHOW ONLY EMPLOYEE RECORDS
    public function displayPersonnelData()
    {
        $query = "SELECT * FROM users where role='employee'";
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
    }
    
    // Fetch single data for edit from user - admin
    public function displayAccountById($user_id)
    {
        $sql = $this->con->prepare("SELECT * FROM users  WHERE user_id = ?");

        $sql->bind_param("i", $user_id);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            echo "Record not found";
        }
    }
}