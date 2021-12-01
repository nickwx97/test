<?php
session_start();

include 'class/ListingManagement.php';

$listingManagementObj = new ListingManagement();

if ((!$_SESSION['role'] == 'employee')) {
    header('location:index.php');
    exit;
}

$errorMsg = "";
$success = true;

$userID = $_POST['user_id'];
$employee_id = $_POST['employee_id'];
$listing_id = $_POST['listing_id'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    function test_input($data){
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
        if (strlen($listing_content) <= 40) {
            $errorMsg .= "listing_content too short.<br>";
            $success = false;
        }
        else if (strlen($listing_content) >= 700) {
            $errorMsg .= "listing_content too long.<br>";
            $success = false;
        }

    }
    if(!empty($_POST['updateListingToken'])){
        if(hash_equals($_SESSION['updateListing-csrf'], $_POST['updateListingToken'])){
            if (isset($_POST["submit"]) && $success == true) {
                $userID = $_POST['user_id'];
                $employee_id = $_POST['employee_id'];

                $newdata['listing_content'] = $listing_content;
                if ($listingManagementObj->updateListingRecord($newdata)) {
                    $success = "Listing updated Successfully ";
                    $_SESSION['updateListing-csrf'] = bin2hex(random_bytes(32));
                } else {
                    $errorMsg = "Listing update failed please try again!";
                    $_SESSION['updateListing-csrf'] = bin2hex(random_bytes(32));
                }
            } else {
                $errorMsg = "Listing update failed please try again!";
                $_SESSION['updateListing-csrf'] = bin2hex(random_bytes(32));
            }
        } else {
            $errorMsg = "Listing update failed please try again!";
            $_SESSION['updateListing-csrf'] = bin2hex(random_bytes(32));
        }
    } else {
        $errorMsg = "Listing update failed please try again!";
        $_SESSION['updateListing-csrf'] = bin2hex(random_bytes(32));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Update Listings</title>
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
    <h2>Update Listing Details</h2>
    <?php
    if (!empty($errorMsg)) {
        echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>  $errorMsg
                  </div>";
        echo ("<button class='btn btn-danger' onclick=\"location.href='updateListings.php?editId=$listing_id'\">Return to Listings</button>");
    } elseif (!empty($success)) {
        echo "<div class='alert alert-success alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>$success
                  </div>";
        echo ("<button class='btn btn-primary' onclick=\"location.href='employee.php'\">Return to employee page</button>");
    }
    ?>
    <?php
    include "./footer.php";