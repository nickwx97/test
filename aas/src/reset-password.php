<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Registration</title>
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
    include_once "database.php";
    ?>
    <br />

<?php
$dbObj = new Database();
$conn = $dbObj->connection();

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$error = '';

function resetPassword($newpass,$useremail)
{

    $email = ($useremail);
    $password = ($newpass);
    $EncryptPassword = password_hash($password, PASSWORD_DEFAULT);
    $dbObj = new Database();
    $conn = $dbObj->connection();


    if (!empty($email) && !empty($password)) {
        $sql = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $sql->bind_param("ss", $EncryptPassword, $email);
        $sql->execute();
        $sql->close();

        $sql2 = $conn->prepare("UPDATE password_reset_temp SET used=used+1 WHERE email=?");
        $sql2->bind_param("s", $email);
        $sql2->execute();
        $sql2->close();
    }
}


if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"]) && ($_GET["action"]=="reset") && !isset($_POST["action"])){
  $key = test_input($_GET["key"]);
  $email = test_input($_GET["email"]);
  date_default_timezone_set('Asia/Singapore');
  $curDate = date("Y-m-d H:i:s");
  $sql = $conn->prepare("SELECT * FROM password_reset_temp WHERE email = ? AND keyValue =?");
  $sql->bind_param("ss", $email,$key);
  $sql->execute();
  $result = $sql->get_result();
  if ($result->num_rows === 0) {
      
        $error .= '<h2>Invalid Link</h2>
                    <p>The link is invalid/expired. Either you did not copy the correct link
                    from the email, or you have already used the key in which case it is 
                    deactivated.</p>
                    <p><a href="https://aas.sitict.net/forgetPassword.php">
                    Click here</a> to reset password.</p>';
    }   
    
    else{
        while ($data = $result->fetch_assoc()) {
            $expDate = $data['expDate'];
            $used = $data['used'];
        }

        if ($curDate > $expDate || $used !== 0)
        {
            
            $error .= '<h2>Invalid Link</h2>
                    <p>The link is invalid/expired. Either you did not copy the correct link
                    from the email, or you have already used the key in which case it is 
                    deactivated.</p>
                    <p><a href="https://aas.sitict.net/forgetPassword.php">
                    Click here</a> to reset password.</p>';
            echo $error;
        }

        else
        {
             ?>    
            <div class="container">
                <section class="py-5">
                    <div class="container my-5">
                        <h2>Change Password</h2>
                        <form method="post" action="reset-password.php">
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="pass1" name="pass1" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Confirm Password:</label>
                                <input type="password" class="form-control" id="pass2" name="pass2" required>
                            </div>
                            <input type="hidden" name="email" value="<?php echo $email;?>"/>
                            <input type="hidden" name="action" value="update"/>
                            <input type="submit" name="submit" class="btn btn-success" style="float:left;" value="Update">
                            <input type="button" name="cancel" class="btn btn-danger" style="float:left;" value="Cancel"
                                formnovalidate onclick="location.href='index.php'">  </form>
                        </form>
                    </div>
                </section>
            </div>
            <?php
        }
    }
}



if(isset($_POST["action"]) && ($_POST["action"]=="update"))
{
    $errorMsg="";
    $success = true;
    $pass1 = test_input($_POST["pass1"]);
    $pass2 = test_input($_POST["pass2"]);
    $email = test_input($_POST["email"]);

    // validation for password
    if (empty($pass1) || empty($pass2)) {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    } 
    
    if (strlen($_POST['pass1']) < 8 || strlen($_POST['pass1']) > 30) {
        $errorMsg .= "Your password must be between 8 and 30 characters!<br>";
        $success = false;
    } 
    
    if (!preg_match("#[0-9]+#", $_POST['pass1'])) {
        $errorMsg .= "Your password must contain at least 1 number!<br>";
        $success = false;
    } 
    
    if (!preg_match("#[A-Z]+#", $_POST['pass1'])) {
        $errorMsg .= "Your password must contain at least 1 uppercase letter!<br>";
        $success = false;
    }
    
    if (!preg_match("#[a-z]+#", $_POST['pass1'])) {
        $errorMsg .= "Your password must contain at least 1 lowercase letter!<br>";
        $success = false;
    } 
    
    if (preg_match("#[\W]+#", $_POST['pass1'])) {
        $errorMsg .= "Your password must not contain any special characters!<br>";
        $success = false;
    }

    if ($pass1 !== $pass2){
        $errorMsg .= "The passwords entered do not match!<br>";
        $success = false;
    }
    
    if ($success != false){
        resetPassword($pass1,$email);
        echo '<div class="success"><p>Congratulations! Your password has been updated successfully.</p>
        <p><a href="https://aas.sitict.net/login.php">
        Click here</a> to Login.</p></div><br />';
    }

    else{
        echo '<div class="error"><p>Your password has not been resetted successfully due to the following:.</p>
        <p>' . $errorMsg . '</p>
        <p><a href="https://aas.sitict.net/forgetPassword.php">
        Click here</a> to reset your password again.</p></div><br />';
    }
}
?>