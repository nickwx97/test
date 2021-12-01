<?php
// session_start();
include_once "database.php";

class IListing
{
    public $listingTable;
    public $dbObj;
    public $con;

    public function __construct()
    {
        $this->listingTable = "listing";
        $this->dbObj = new Database();
        $this->con = $this->dbObj->connection();
    }

    // Fetch all listing records for show (for guest)
    public function displayAllListingData()
    {
        $query = "SELECT * FROM listing inner join employee
			on listing.employee_id=employee.employee_id
			inner join
			users
			on employee.user_id=users.user_id
			";
        $result = $this->con->query($query);
        
        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            echo "<br/>No found records";
        }
    }

    // Fetch listing records for show listing (for employee)
    public function displayListingData($emp_id)
    {
        $sql = $this->con->prepare("SELECT * FROM listing inner join employee on listing.employee_id=employee.employee_id inner join users on employee.user_id=users.user_id WHERE employee.employee_id = ?");
        $sql->bind_param("i", $emp_id);
        $sql->execute();
        $result = $sql->get_result();
        
        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            echo "<br/>No found records";
        }
    }
   
   // Fetch single data for edit from listing table
    public function displayListingById($listing_id)
    {
        $sql = $this->con->prepare("SELECT * FROM listing inner join employee on listing.employee_id=employee.employee_id inner join users on employee.user_id = users.user_id WHERE listing_id = ?");
        $sql->bind_param("i", $listing_id);
        $sql->execute();
        $result= $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            echo "Record not found";
        }
        $sql->close();
    }
}