<?php

session_start();
//generate csrf token for change password form
if (empty($_SESSION['ChangePwd-csrf'])){
    $_SESSION['ChangePwd-csrf'] = bin2hex(random_bytes(32));
}
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
    header("location:changePassword.php?editId=".$_SESSION['user_id']);
    exit;
}

// redirect user to login if role is not ADMIN or employee or guest -->
if (!$_SESSION['role'] == 'admin' || !$_SESSION['role'] == 'employee' || !$_SESSION['role'] == 'guest') {
    header('location:login.php');
    exit;
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
    <title>Team AAS - Change Password</title>
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
            <h2>Change Password</h2>
            <form method="post" action="changePasswordProcess.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="password">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" required>
                </div>

                <input type="hidden" class="form-control" id="user_id" name="user_id"
                    value="<?php echo $user['user_id']; ?>">
                <input type="hidden" class="form-control" name="changePwdToken"
                    value="<?php echo $_SESSION['ChangePwd-csrf']; ?>">
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