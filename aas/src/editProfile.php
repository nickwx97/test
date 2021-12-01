<?php

session_start();

include 'class/IUser.php';
$iuser = new IUser();

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

if (!($_GET['editId'] == $_SESSION['user_id']))
{    
    header("location:editProfile.php?editId=".$_SESSION['user_id']);
    exit;
}

if (!$_SESSION['role'] == 'admin' || !$_SESSION['role'] == 'employee' || !$_SESSION['role'] == 'guest') {
    header('location:login.php');
    exit;
}

// csrf token for editProfile form
if (empty($_SESSION['editProfile-csrf'])){
    $_SESSION['editProfile-csrf'] = bin2hex(random_bytes(32));
}

if( $_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { 
    //redirect to logout.php
    header('Location: http://aas.sitict.net/logout.php'); 
} else{ //if we haven't expired:
    $_SESSION['last_activity'] = time(); 
}

// Edit customer record
if (isset($_GET['editId']) && !empty($_GET['editId'])) {
    $editId = $_GET['editId'];
    $user = $iuser->displayaRecordById($editId);
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
            <form method="post" action="editProfileProcess.php" enctype="multipart/form-data">
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

                <input type="hidden" class="form-control" id="user_id" name="user_id"
                    value="<?php echo $user['user_id']; ?>">
                <input type="hidden" class="form-control" name="editProfileToken"
                    value="<?php echo $_SESSION['editProfile-csrf']; ?>">
                <input type="submit" name="submit" class="btn btn-success" style="float:left;" value="Update">
                <input type="button" name="cancel" class="btn btn-danger" style="float:left;" value="Cancel"
                    formnovalidate onclick="location.href='index.php'">
            </form>
        </div>
    </section>
</body>
<?php
include "./footer.php";
?>

</html>