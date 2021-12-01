<?php

include 'class/AppointmentManagement.php';
include_once "database.php";

$apptManagementObj = new AppointmentManagement();

if ((!$_SESSION['role'] == 'guest')) {
    header('location:index.php');
    exit;
}

$duration = 60;
$cleanup = 0;
$start = "09:00";
$end = "18:00";

if (isset($_GET['addId']) && !empty($_GET['addId'])) {
    $addId = $_GET['addId'];
    $appt = $apptManagementObj->displayListingById($addId);
}
if (isset($_GET['appointment_id']) && !empty($_GET['appointment_id'])) {
    $editId = $_GET['appointment_id'];
}
if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $sql = $apptManagementObj->con->prepare("select * from appointment where appointment_date_time=?");
    $sql->bind_param("s", $date);
    $bookings = array();
    if ($sql->execute()) {
        $result = $sql->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row['timeslot'];
            }
            $sql->close();
        }
    }
}

$errorMsg = "";
$success = true;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $venue = test_input($_POST["venue"]);

    // validation for venue
    if (empty($_POST['venue'])) {
        $errorMsg .= "venue is required.<br>";
        $success = false;
    } else {
        #check if its letters or space
        if (preg_match("/[^a-zA-Z\s]+$/", $venue)) {
            # means no letter or space
            $errorMsg .= "Invalid venue.<br>";
            $success = false;
        } else {
            #means got letter
            if (strlen($venue) >= 40) {
                $errorMsg .= "venue too long.<br>";
                $success = false;
            }
        }
    }
    if(!empty($_POST['updateBookingToken'])){
        if(hash_equals($_SESSION['updateBooking-csrf'], $_POST['updateBookingToken'])){
            if (isset($_POST["submit"]) && $success == true) {
                $userID = $_POST['user_id'];
                $newdata['venue'] = $_POST['venue'];
                $newdata['timeslot'] = $_POST['timeslot'];

                if ($apptManagementObj->updateApptRecord($newdata)) {
                    $success = "Appointment added Successfully ";
                    $_SESSION['updateBooking-csrf'] = bin2hex(random_bytes(32));
                } else {
                    $errorMsg = "Appointment creation failed! Please try again!";
                    $_SESSION['updateBooking-csrf'] = bin2hex(random_bytes(32));
                }
            } else {
                $errorMsg = "Appointment update failed! Please try again!";
                $_SESSION['updateBooking-csrf'] = bin2hex(random_bytes(32));
            }
        } else {
            $errorMsg = "Appointment update failed! Please try again!";
            $_SESSION['updateBooking-csrf'] = bin2hex(random_bytes(32));
        }
    } else {
        $errorMsg = "Appointment update failed! Please try again!";
        $_SESSION['updateBooking-csrf'] = bin2hex(random_bytes(32));
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Appointment Update Details</title>
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

    <h2>Appointment Update Details</h2>
    <?php
    if (!empty($errorMsg)) {
        echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>  $errorMsg
                  </div>";
        echo ("<button class='btn btn-primary' onclick=\"location.href='guestBooking.php'\">Return to booking page</button>");
    } elseif (!empty($success)) {
        echo "<div class='alert alert-success alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>$success
                  </div>";
        echo ("<button class='btn btn-primary' onclick=\"location.href='myBooking.php'\">Return to my booking page</button>");
    }
    ?>

    <?php
    include "./footer.php";