<?php

include 'class/UserManagement.php';
include 'sendEmails.php';
$userManagementObj = new UserManagement();

$success = true;
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST['user_id'];

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    $password = test_input($_POST["password"]);
    $confirmPassword = test_input($_POST["confirmpassword"]);   
    // validation for password requirement
    if (empty($password) or empty($confirmPassword)) {
        $errorMsg .= "Empty passwords are not allowed.<br>";
        $success = false;
    } else {
        if (strlen($password) < 8 || strlen($password) > 30) {
            $errorMsg .= "Your password must be between 8 and 30 characters!<br>";
            $success = false;
        } elseif (!preg_match("#[0-9]+#", $password)) {
            $errorMsg .= "Your password must contain at least 1 number!<br>";
            $success = false;
        } elseif (!preg_match("#[A-Z]+#", $password)) {
            $errorMsg .= "Your password must contain at least 1 uppercase letter!<br>";
            $success = false;
        } elseif (!preg_match("#[a-z]+#", $password)) {
            $errorMsg .= "Your password must contain at least 1 lowercase letter!<br>";
            $success = false;
        } elseif (preg_match("#[\W]+#", $password)) {
            $errorMsg .= "Your password must not contain any special characters!<br>";
            $success = false;
        }
    }

    if(!empty($_POST['changePwdToken'])){
        if(hash_equals($_SESSION['ChangePwd-csrf'], $_POST['changePwdToken'])){
            if (isset($_POST["submit"]) && $success == true) {
                $user_id = $_POST['user_id'];
                $newdata['password'] = $_POST['password'];
                if ($password === $confirmPassword) {
                    if ($userManagementObj->updatePassword($newdata)) {
                        $success = "Password updated successfully";
                        $log = "Password updated successfully by user_id: ".$user_id;
                        updateProfileEmailNotification($email);
                        logger($log);
                        $_SESSION['ChangePwd-csrf'] = bin2hex(random_bytes(32)); // regenerate csrf token after form submission
                    } 
                }else {
                    $errorMsg = "Password update failed please try again!";
                    $log = "Password update failed by user_id: ".$user_id;
                    logger($log);
                    $_SESSION['ChangePwd-csrf'] = bin2hex(random_bytes(32));
                }
            }
        }
        else {
            $errorMsg = "Password update failed please try again!";
            $log = "Password update failed by user_id: ".$user_id;
            logger($log);
            $_SESSION['ChangePwd-csrf'] = bin2hex(random_bytes(32));
        }
    } else {
        $errorMsg = "Password update failed please try again!";
        $log = "Password update failed by user_id: ".$user_id;
        logger($log);
        $_SESSION['ChangePwd-csrf'] = bin2hex(random_bytes(32));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Update Password Details</title>
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

    <h2>Update Password Details</h2>
    <section class="py-5">
        <div class="container my-5">
            <?php
if (!empty($errorMsg)) {
    echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>  $errorMsg
                  </div>";
    echo ("<button class='btn btn-danger' onclick=\"location.href='changePassword.php?editId=$user_id'\">Return to Change Password</button>");
} elseif (!empty($success)) {
    echo "<div class='alert alert-success alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>$success
                  </div>";
    echo ("<button class='btn btn-primary' onclick=\"location.href='index.php?editId=$user_id'\">Return to Index page</button>");
}
?>
        </div>
    </section>

    <?php
include "./footer.php";