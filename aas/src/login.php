<?php
include "logging.php";
include_once "class/UserManagement.php";

// csrf token for editProfile form
if (empty($_SESSION['login-csrf'])){
    $_SESSION['login-csrf'] = bin2hex(random_bytes(32));
}

$userManagementObj = new UserManagement();

$success = true;
$errorMsg = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);

    // validation for email
    if (empty($email)) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else {
        // Additional check to make sure e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.";
            $success = false;
        }
    }

    //Password Check
    if (empty($password)) {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    }
}
if(!empty($_POST['loginToken'])){
    if(hash_equals($_SESSION['login-csrf'], $_POST['loginToken'])){
        if (isset($_POST['submit']) && $success === true) {
            $newdata['email'] = $_POST['email'];
            $newdata['password'] = $_POST['password'];

            //or run normal login method
            if ($userManagementObj->verifyCredentials($newdata)) {
                $_SESSION["email"] = $_POST['email'];
                $_SESSION["password"] = $_POST['password'];
                $log = "Successful login attempt by user: ".$newdata['email'];
                unset($_SESSION['login-csrf']);
                logger($log);
                header("Location:2fa.php");
            } else {
                $errorMsg = "Wrong Password or Email";
                $_SESSION['login-csrf'] = bin2hex(random_bytes(32));
                //log the login failure
                $log = "Failed login attempt by user: ".$newdata['email'];
                logger($log);
            }
        } 
    } else {
        $errorMsg = "Wrong Password or Email";
        $_SESSION['login-csrf'] = bin2hex(random_bytes(32));
        //log the login failure
        $log = "Failed login attempt by user: ".$newdata['email'];
        logger($log);
    }
} else {
    $_SESSION['login-csrf'] = bin2hex(random_bytes(32));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/icon" href="asset/mader3.jpg" />
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

    <?php
    if (!empty($errorMsg)) {
        echo "<div class='alert alert-danger alert-dismissible'>
                  <button type='button' class='close' data-dismiss='alert'>&times;</button>$errorMsg
              </div>";
    }
    ?>
    <section class="py-5">
        <div class="container my-5">
            <h2>Login</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <!-- <form method="post" action="2fa.php"> -->
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" required
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter password"
                        name="password" required>
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control" name="loginToken"
                        value="<?php echo $_SESSION['login-csrf']; ?>">
                </div>
                <input type="submit" name="submit" class="btn btn-success btn-block" value="Sign In">
                <br />

                <input type="button" name="cancel" class="btn btn-primary" style="float:left;"
                    value="Click here to Register" formnovalidate onclick="location.href='registration.php'">
                <input type="button" name="cancel" class="btn btn-danger" style="float:left;" value="Forget Password"
                    formnovalidate onclick="location.href='forgetPassword.php'">
            </form>
        </div>
    </section>

    <?php
include "./footer.php";
?>
</body>


</html>