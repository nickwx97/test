<?php

session_start();

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

// csrf token for employeeProfile form
if (empty($_SESSION['empProfile-csrf'])){
    $_SESSION['empProfile-csrf'] = bin2hex(random_bytes(32));
}
include 'class/IUser.php';
$iuser = new IUser();


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

// Edit customer record
if (isset($_GET['editId']) && !empty($_GET['editId'])) {
    $editId = $_GET['editId'];
    $user = $iuser->displayEmployeeRecordById($editId);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Edit Profile</title>
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

    <section class="py-5">
        <div class="container my-5">
            <h2>Edit Profile</h2>
            <form method="post" action="employeeProfileProcess.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required
                        pattern="^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$"
                        value="<?php echo $user['first_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required
                        pattern="^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$" value="<?php echo $user['last_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?php echo $user['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="contact_no">Contact No:</label>
                    <input type="text" class="form-control" id="contact_no" name="contact_no" required
                        pattern="[6,8,9][0-9]{7}" value="<?php echo $user['contact_no']; ?>">
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $user['dob']; ?>">
                </div>
                <div class="form-group">
                    <label for="profile_pic">Profile Picture:</label>
                    <img src="images/<?php echo $user['profile_pic']; ?>" style="width:300px;height:200px;">
                    <br /> <br />
                    <input type="file" class="form-control-file" name="profile_pic" accept=".png,.jpg,.jpeg"
                        value="<?php echo $user['profile_pic']; ?>">
                </div>
                <div class="form-group">
                    <label for="job_title">Job Title:</label>
                    <input type="text" class="form-control" id="job_title" name="job_title" 
                        pattern="^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$" value="<?php echo $user['job_title']; ?>">
                </div>
                <div class="form-group">
                    <label for="years_exp">Years of Experience:</label>
                    <input type="number" class="form-control" id="years_exp" name="years_exp" min="1" max="100"
                        value="<?php echo $user['years_exp']; ?>">
                </div>
                <div class="form-group">
                    <label for="specialisation">Specialisation:</label>
                    <input type="text" class="form-control" id="specialisation" name="specialisation"
                        pattern="^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$" value="<?php echo $user['specialisation']; ?>">
                </div>
                <div class="form-group">
                    <label for="linkedin">Linkedin:</label>
                    <input type="url" class="form-control" id="linkedin" name="linkedin" size="30"
                        value="<?php echo $user['linkedin']; ?>">
                </div>

                <input type="hidden" class="form-control" id="user_id" name="user_id"
                    value="<?php echo $user['user_id']; ?>">
                <input type="hidden" class="form-control" id="employee_id" name="employee_id"
                    value="<?php echo $user['employee_id']; ?>">
                <input type="hidden" class="form-control" name="empProfileToken"
                    value="<?php echo $_SESSION['empProfile-csrf']; ?>">
                <input type="submit" name="submit" class="btn btn-primary" style="float:left;" value="Update">
                <input type="submit" name="cancel" class="btn btn-danger" style="float:left;" value="Cancel"
                    formnovalidate>
            </form>
        </div>
    </section>
</body>
<?php
include "./footer.php";
?>




</html>