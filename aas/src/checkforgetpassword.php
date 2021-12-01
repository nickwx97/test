<?php
include './sendEmails.php';

$success = true;
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    if(!empty($_POST['g-recaptcha-response']))
    {
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
            if($responseData->success)
            {
                $email = test_input($_POST["email"]);
                // validation for email
                if (empty($_POST['email'])) {
                    $errorMsg .= "Email is required.<br>";
                    $success = false;
                } 
                
                else {
                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        $errorMsg .= "Invalid email format.";
                        $success = false;
                    }
                }
            
                if (isset($_POST["submit"]) && $success == true) {
                    $email = $_POST['email'];
                    $result = sendForgetPasswordEmail($email);
                    
                    if ($result!= '')
                    {
                        $errorMsg = $result;
                        $success = false;                    
                    }
                } 
                else {
                    $errorMsg = "Password update failed";
                    $success = false;
                }
            }
    }
    else{
        $success = false;
        $errorMsg = "Google Recaptcha failed";
    }
               
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Check Forget Password</title>
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

    <div class="container">
        <?php
if ($success == true) {
    echo "<p>We will send you an email shortly to $email</p>";
    echo "<p>Please check your Spam or Junk Mail</p>";
    echo "<form action ='login.php'>";
    echo "<input type='submit' value='Return to Login Page'>";
    echo "<br>";
} else {
    echo "<h2>Oops!</h2>";
    echo "<h3>The following errors were detected: </h3>";
    echo "<p>$errorMsg</p>";
}
?>
    </div>
</body>

<?php
include "./footer.php";
?>

</html>