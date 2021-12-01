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

if (($_SESSION['role'] == 'guest')) {
    header('location:myBooking.php');
    exit;
}
if ((!$_SESSION['role'] == 'employee')) {
    header('location:index.php');
    exit;
}

if($_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { 
    //redirect to logout.php
    header('Location: http://aas.sitict.net/logout.php'); 
} else{ //if we haven't expired:
    $_SESSION['last_activity'] = time(); 
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Employee Appointment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>

<body>

    <?php
    include "./navbar.php";
    ?>

    
    <section class="py-5">
        <div class="container my-5">
            <h1 class="mt-5 text-center">Welcome Back, <?php echo $_SESSION['username']; ?>
                <br />Role:<?php echo $_SESSION['role']; ?>
            </h1>
            <table class="table-responsive" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>emp id</th>
                        <th>Appointment</th>
                        <th>Timeslot</th>
                        <th>Venue</th>
                        <th>Consultant</th>
                        <th>Contact No</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $iappts = new IAppointment();
                $iappts = $iappts->displayApptData();
                if ($iappts != null) {
                    foreach ($iappts as $appt) {
                ?>
                    <tr>
                        <td><?php echo $appt['employee_id'] ?></td>
                        <td><?php echo $appt['appointment_date_time'] ?></td>
                        <td><?php echo $appt['timeslot'] ?></td>
                        <td><?php echo $appt['venue'] ?></td>
                        <td><?php echo $appt['first_name'] ?></td>
                        <td><?php echo $appt['contact_no'] ?></td>
                        <td><?php echo $appt['email'] ?></td>
                    </tr>
                    <?php }
                } else { ?>
                    <?php echo 'No records available';
                } ?>
                </tbody>
            </table>
            <br />
            <?php
        $appts = $iappt->viewAnalytics();

        $chart_data = '';
        if ($appts != null) {
            foreach ($appts as $appt) {
                $chart_data .= "{ appointment_date_time:'" . $appt["appointment_date_time"] . "', appointment_id:" . $appt["appointment_id"] . ",}, ";
            }
        }
        $chart_data = substr($chart_data, 0, -2);
        ?>
            <h3 align="center">No of Appointments for the past 6 months </h3>
            <div id="chart"></div>
        </div>
    </section>
</body>

<?php
include "./footer.php";
?>

</html>

<script>
Morris.Bar({
    element: 'chart',
    data: [<?php echo $chart_data; ?>],
    xkey: 'appointment_date_time',
    ykeys: ['appointment_id'],
    labels: ['total'],
    hideHover: 'auto',
    stacked: true
});
</script>