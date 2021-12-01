<?php

include 'class/AppointmentManagement.php';
include_once "database.php";

$apptManagementObj = new AppointmentManagement();

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

//generate csrf token for change password form
if (empty($_SESSION['createBooking-csrf'])){
    $_SESSION['createBooking-csrf'] = bin2hex(random_bytes(32));
}

if($_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { 
    //redirect to logout.php
    header('Location: http://aas.sitict.net/logout.php'); 
} 

else{ //if we haven't expired:
    $_SESSION['last_activity'] = time(); 
}

$duration = 60;
$cleanup = 0;
$start = "09:00";
$end = "18:00";

if (isset($_GET['addId']) && !empty($_GET['addId'])) {
    $addId = $_GET['addId'];
    $appt = $apptManagementObj->displayListingById($addId);
}
if (isset($_GET['employee_id']) && !empty($_GET['employee_id'])) {
    $editId = $_GET['employee_id'];
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Appointment Booking</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/guest.css">
</head>

<body>
    <?php
    include "./navbar.php";
    ?>

    <h1 class="mt-5 text-center">Welcome back, <?php echo $_SESSION['username']; ?>
        <br />
        Role:<?php echo $_SESSION['role']; ?>
    </h1>

    <section class="py-5">
        <div class="container my-5">
            <h1 class="text-center">Book for date: <?php echo date('d/m/Y', strtotime($date)); ?></h1>
            <input type="button" name="cancel" class="btn btn-info" value="Choose another day" formnovalidate
                onclick="location.href='guestAppt.php?employee_id=<?php echo $_GET['employee_id'] ?>'">
            <hr>
            <div class="row">
                <?php $appts = $apptManagementObj->timeslots($duration, $cleanup, $start, $end);
            foreach ($appts as $ts) {
            ?>
                <div class="col-md-2">
                    <div class="form-group">
                        <?php if (in_array($ts, $bookings)) { ?>
                        <button class="btn btn-danger"> <?php echo $ts; ?></button>
                        <?php } else { ?>
                        <button class="btn btn-success book" data-timeslot="<?php echo $ts; ?>">
                            <?php echo $ts; ?></button>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class=" modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Booking <span id="slot"></span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <div class="col-mod-12">
                            <form
                                action="guestBookingProcess.php?employee_id=<?php echo $_GET['employee_id'] ?>&date=<?php echo $_GET['date'] ?>"
                                method="post">
                                <div class="form-group">
                                    <label for="timeslot">Timeslot:</label>
                                    <input readonly class="form-control" id="timeslot" name="timeslot">
                                </div>
                                <div class="form-group">
                                    <label for="venue">Venue:</label>
                                    <br />
                                    <label for="venue">Note** Due to COVID-19, venue will be held on Zoom</label>
                                    <input readonly class="form-control" id="venue" name="venue" value="Zoom">
                                </div>

                                <input hidden class="form-control" id="employee_id" name="employee_id"
                                    value="<?php echo $_GET['employee_id']; ?>">

                                <input hidden class="form-control" id="user_id" name="user_id"
                                    value="<?php echo $_SESSION['user_id']; ?>">
                                <input hidden class="form-control" name="createBookingToken"
                                    value="<?php echo $_SESSION['createBooking-csrf']; ?>">
                                <br />
                                <div class="form-group pull-right">
                                    <button class="btn btn-primary btn-block" type="submit" name="submit">
                                        Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(".book").click(function() {
        var timeslot = $(this).attr('data-timeslot');
        $("#slot").html(timeslot);
        $("#timeslot").val(timeslot);
        $("#myModal").modal("show");
    });
    </script>
</body>

<?php
include "./footer.php";
?>

</html>