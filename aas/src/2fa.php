<?php
session_start();

if(!isset($_SERVER['HTTP_REFERER'])){
    header('location:index.php');
    exit;
  }

require_once "vendor/autoload.php";
include_once "class/Otp.php";

$otpObj = new OTPCheck($_SESSION["temp_user_id"]);

if(!($otpObj->checkExists())){
    $otpObj->insertNew();
    header("Location:2fa.php");
    die();
}
if($otpObj->checkExists()){
    if($otpObj->checkGenerated()){
        echo '<section class="py-5">';
        echo '<div class="container my-5">';
        echo '<div class="form-group">';
        echo '<label for="2fa"><h3>Enter OTP code</h3></label>';
        echo '<div>';
        echo '<form method="post" action="2faVerifyHandle.php">';
        echo "<br/ ><input class=\"form-control\" id='otp' type='text' name='otp' pattern='\d*' minlength='6' maxlength='6' required>";
        echo '<div>';
        echo '<section>';
    } else {
        $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        $secret = $g->generateSecret();
        echo '<p>Scan QR with Google Authenticator App and enter OTP code.</p>';
        echo '<form method="post" action="2faCreateHandle.php">';
        echo '<br /><img src="'.$g->getURL($_SESSION["temp_username"], 'aas.sitict.net', $secret).'" />';
        echo "<br/ ><input class=\"form-control\" id='otp' type='text' name='otp' pattern='\d*' minlength='6' maxlength='6' required>";
        $_SESSION['secret'] = $secret;
    }
}
?>

<head>
    <title>Team AAS - Enter OTP Code (2FA)</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/icon" href="asset/mader3.jpg" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<button class='btn btn-success' type="submit">Submit</button>

</form>