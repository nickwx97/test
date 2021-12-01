<?php
include "logging.php";
include_once "class/UserManagement.php";

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
    
    // validation for email
    if (empty($email)) {
        $errorMsg .= "Email is required.<br>";
        $log = "Forget Password Empty Email Attempt";
        logger($log);
        $success = false;
    } else {
        // Additional check to make sure e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.";
            $log = "Forget Password Invalid Email Format Attempt";
            logger($log);
            $success = false;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Forget Password</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
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
            <form action="checkforgetpassword.php" method="post">
                <div class="container">
                    <h2>Forget Password</h2>
                    <p>Please enter your email to get a new password.</p>
                    <p>We will send you an email to reset your password. </p>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email"
                            required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                    </div>

                    <div class="g-recaptcha" data-sitekey="6LcKYgYdAAAAANRq55A_jj7bkeVqRWDGDQnh4gvD"></div>
                    <br>
                    <div class="clearfix">
                        <input type="button" name="cancel" class="btn btn-danger" style="float:left;" value="Cancel"
                            formnovalidate onclick="location.href='login.php'">
                        <input name="submit" type="submit" class="btn btn-primary "></button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</body>
<?php
include "./footer.php";
?>

</html>