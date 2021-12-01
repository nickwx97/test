<!-- getter setter for Listing class -->

<?php
include_once "database.php";

class Listing
{
    // Properties
    public $listing_id;
    public $listing_content;
    public $employee_id;

    // Methods
    public function setListingID($listing_id)
    {
        $this->listing_id = $listing_id;
    }

    public function getListingID()
    {
        return $this->listing_id;
    }

    public function setListingContent($listing_content)
    {
        $this->listing_content = $listing_content;
    }

    public function getListingContent()
    {
        return $this->listing_content;
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