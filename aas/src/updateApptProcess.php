<?php

include 'class/AppointmentManagement.php';

$apptManagementObj = new AppointmentManagement();

// redirect user if they are not guest
if ((!$_SESSION['role'] == 'guest')) {
    header('location:index.php');
    exit;
}
$success = true;
$errorMsg = "";

$appointment_id = $_POST['appointment_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"]) && $success == true) {
        $appointment_id = $_POST['appointment_id'];
        $newdata['appointment_date_time'] = $_POST['appointment_date_time'];
        $newdata['timeslot'] = $_POST['timeslot'];

        if ($apptManagementObj->updateApptRecord($newdata)) {
            $success = "Appointment updated successful";
        } else {
            $errorMsg = "Appointment update failed please try again!";
        }
    }   
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Update Appointment Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>

<body>
    <?php
    include "./navbar.php";
    ?>

    <br />
    <h2>Update Appointment Details</h2>
    <?php
    if (!empty($errorMsg)) {
        echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>  $errorMsg
                  </div>";
        echo ("<button class='btn btn-danger' onclick=\"location.href='myBooking.php?editId=$appointment_id'\">Return to Update records</button>");
    } elseif (!empty($success)) {
        echo "<div class='alert alert-success alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>$success
                  </div>";
        echo ("<button class='btn btn-primary' onclick=\"location.href='myBooking.php'\">Return to appointment page</button>");
    }
    ?>
    <?php
    include "./footer.php";