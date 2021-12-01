<?php
session_start();
include 'class/AppointmentManagement.php';
include 'class/IAppointment.php';

$apptManagementObj = new AppointmentManagement();
$iappt = new IAppointment();

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

if ((!$_SESSION['role'] == 'guest')) {
    header('location:index.php');
    exit;
}

if($_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { 
    //redirect to logout.php
    header('Location: http://aas.sitict.net/logout.php'); 
} 

else{ //if we haven't expired:
    $_SESSION['last_activity'] = time(); 
}

// Edit account record
if (isset($_GET['appointment_id']) && !empty($_GET['appointment_id'])) {
    $editId = $_GET['appointment_id'];
    $appt = $iappt->displayApptById($editId);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Update Appointment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <?php
    include "./navbar.php";
    ?>

    <br />
    <section class="py-5">
        <div class="container my-5">
            <h2 class="center">Update Appointment</h2>
            <input type="hidden" class="form-control" id="user_id" name="user_id"
                value="<?php echo $appt['user_id']; ?>">
            <input type="hidden" class="form-control" id="appointment_id" name="appointment_id"
                value="<?php echo $appt['appointment_id']; ?>">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div id="calendar">
                            <?php
                        $appointment_id = $_GET['appointment_id'];
                        ?>
                            <?php
                        $dateComponents = getdate();
                        if (isset($_GET['month']) && isset($_GET['year'])) {
                            $month = $_GET['month'];
                            $year = $_GET['year'];
                        } else {
                            $month = $dateComponents['mon'];
                            $year = $dateComponents['year'];
                        }
                        echo $apptManagementObj->build_calendar_update($month, $year);
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<?php
include "./footer.php";
?>

</html>