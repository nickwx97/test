<?php
session_start();
include_once "database.php";

class ListingManagement
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

    // Insert listings
    public function insertListingData($postData)
    {
        $listing_content = $this->con->real_escape_string($_POST['listing_content']);
        $employee_id = $this->con->real_escape_string($_POST['employee_id']);
        // $user_id = $this->con->real_escape_string($_POST['user_id']);
    
        if (!empty($employee_id) && !empty($postData)) {
            $sql = $this->con->prepare("INSERT INTO listing (listing_content, employee_id)
			VALUES(?,?)");
            $sql->bind_param("si", $listing_content, $employee_id);
            $sql->execute();
            $result = $sql->get_result();

            if ($sql == true) {
                return true;
            } else {
                return false;
            }
            $sql->close();
        }
    }

    // Update Listing
    public function updateListingRecord($postData)
    {
        $listing_content = $this->con->real_escape_string($_POST['listing_content']);
        $employee_id = $this->con->real_escape_string($_POST['employee_id']);
        $listing_id = $this->con->real_escape_string($_POST['listing_id']);
       
        if (!empty($listing_id) && !empty($postData)) {
            $sql = $this->con->prepare("UPDATE listing SET listing_content=? WHERE listing_id=?");
            $sql->bind_param("si", $listing_content, $listing_id);
            $sql->execute();
            $result = $sql->get_result();
            $sql->close();

            if ($sql == true) {
                return true;
            } else {
                return false;
            }

            $sql->close();
        }
    }

    // Delete listing
    public function deleteListingRecord($listing_id)
    {
        $sql = $this->con->prepare("DELETE FROM listing WHERE listing_id=?");
        $sql->bind_param("i", $listing_id);
        $sql->execute();
        $result = $sql->get_result();
        $sql->close();

        if ($sql == true) {
            header("Location:employee.php?msg6=delete");
        } else {
            echo "Listing update failed PLEASE try again!";
        }
    }

    // Search listing
    public function searchListingRecord()
    {
        $search = "%{$_POST['search']}%";

        $sql = $this->con->prepare("SELECT * FROM listing inner join employee
			on listing.employee_id=employee.employee_id
			inner join
			users
			on employee.user_id=users.user_id WHERE employee.specialisation like ?");

        $sql->bind_param("s", $search);
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
        $sql->close();
    }
}

$listingManagementObj = new ListingManagement();