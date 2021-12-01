<?php
session_start();

include 'class/ListingManagement.php';

// redirect user to login if role is not EMPLOYEE
if (!$_SESSION['role'] == 'employee') {
    header('location:login.php');
    exit;
}

$listingManagementObj = new ListingManagement();

$errorMsg = "";
$success = true;

$employee_id = $_POST['employee_id'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
  
    $listing_content = test_input($_POST["listing_content"]);

    // validation for listing_content
    if (empty($listing_content)) {
        $errorMsg .= "listing_content is required.<br>";
        $success = false;
    } else {
        #check if its letters or space
        if (preg_match("/[^a-zA-Z\s]+$/", $listing_content)) {
            # means no letter or space
            $errorMsg .= "Invalid listing content.<br>";
            $success = false;
        } else {
            #means got letter
            if (strlen($listing_content) <= 40) {
                $errorMsg .= "Listing content is too short.<br>";
                $success = false;
            }
            else if (strlen($listing_content) >= 700) {
                $errorMsg .= "Listing content is too long.<br>";
                $success = false;
            }
        }
    }

    if(!empty($_POST['addListToken'])){
        if(hash_equals($_SESSION['addListings-csrf'], $_POST['addListToken'])){
            if (isset($_POST["submit"]) && $success == true) {
                $employee_id = $_POST['employee_id'];
                $newdata['listing_content'] = $listing_content;
        
                if ($listingManagementObj->insertListingData($newdata)) {
                    $success = "Listing added successfully ";
                    $_SESSION['addListings-csrf'] = bin2hex(random_bytes(32)); //regenerate csrf token after form submission
                } else {
                    $errorMsg = "Listing creation failed! Please try again!";
                    $_SESSION['addListings-csrf'] = bin2hex(random_bytes(32));
                }
            } 
        } else {
            $errorMsg = "Listing creation failed! Please try again!";
            $_SESSION['addListings-csrf'] = bin2hex(random_bytes(32));
        }
    } else {
        $errorMsg = "Listing creation failed! Please try again!";
        $_SESSION['addListings-csrf'] = bin2hex(random_bytes(32));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Add Listings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php
include "./navbar.php";
?>

    <br />


    <section class="py-5">
        <div class="container my-5">
            <h2>Add Listing Details</h2>
            <?php
if (!empty($errorMsg)) {
    echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>  $errorMsg
                  </div>";
    echo ("<button class='btn btn-danger' onclick=\"location.href='addListings.php?addId=$employee_id'\">Return to Listings</button>");
} elseif (!empty($success)) {
    echo "<div class='alert alert-success alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>$success
                  </div>";
    echo ("<button class='btn btn-primary' onclick=\"location.href='employee.php'\">Return to employee page</button>");
}

?>
        </div>
    </section>

    <?php
include "./footer.php";