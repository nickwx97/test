<?php
session_start();
include 'class/AppointmentManagement.php';
include 'class/IAppointment.php';

if (!$_SESSION['user_agent'] == $_SERVER['HTTP_USER_AGENT']){
    header('location:logout.php');
    exit;
}

else{
    if (!$_SESSION['remote_ip'] == $_SERVER['REMOTE_ADDR']){
        header('location:logout.php');
        exit;
    }
}

// redirect user to login if role is not guest
if ((!$_SESSION['role'] == 'guest')) {
    header('location:login.php');
    exit;
}

// redirect employee to myAppt if they try to access myBooking.php
if (($_SESSION['role'] == 'employee')){
    header('location:myAppt.php');
    exit;
}

if($_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { 
    //redirect to logout.php
    header('Location: http://aas.sitict.net/logout.php'); 
} else{ //if we haven't expired:
    $_SESSION['last_activity'] = time(); 
}


$apptManagementObj = new AppointmentManagement();

// Delete record from table
if (isset($_GET['deleteId']) && !empty($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    $apptManagementObj->deleteApptRecord($deleteId);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - View My Bookings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="css/index.css">

</head>

<body>
    <?php
    include "./navbar.php";
    ?>

    <br />

    <section class="py-5">
        <div class="container my-5">
            <?php
        if (isset($_GET['msg1']) == "insert") {
            echo "<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              Appointment created successfully
            </div>";
        }
        if (isset($_GET['msg2']) == "update") {
            echo "<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
             Appointment updated successfully
            </div>";
        }
        if (isset($_GET['msg3']) == "delete") {
            echo "<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              Appointment deleted successfully
            </div>";
        }
        ?>

            <h1 class="mt-5 text-center">Welcome Back, <?php echo $_SESSION['username']; ?>
                <table class="table-responsive" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th>Appointment ID</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Venue</th>
                            <th>Consultant</th>
                            <th>Contact No</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                    $iappts = new IAppointment();
                    $appts = $iappts->displayApptData();
                    if ($appts != null) {
                        foreach ($appts as $appt) {
                    ?>
                        <tr>
                            <td><?php echo $appt['appointment_id'] ?></td>
                            <td><?php echo $appt['appointment_date_time'] ?></td>
                            <td><?php echo $appt['timeslot'] ?></td>
                            <td><?php echo $appt['venue'] ?></td>
                            <td><?php echo $appt['first_name'] ?></td>
                            <td><?php echo $appt['contact_no'] ?></td>
                            <td><?php echo $appt['email'] ?></td>
                            <td>
                                <a href="updateAppt.php?editId=<?php echo $appt['appointment_id'] ?>&appointment_id=<?php echo $appt['appointment_id'] ?>
" style="color:green;float:right;font-size:28px">
                                    <i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp

                                <a href="myBooking.php?deleteId=<?php echo $appt['appointment_id'] ?>" style="color:red"
                                    onclick="confirm('Are you sure want to delete this appointment???')">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                            </td>
                        </tr>
                        <?php }
                    } else { ?>
                        <?php echo 'No records available';
                    } ?>
                    </tbody>
                </table>
        </div>
    </section>
</body>

<?php
include "./footer.php";
?>

</html>