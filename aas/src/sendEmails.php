<?php
require_once './vendor/autoload.php';
include_once 'database.php';
include_once 'logging.php';

if(!isset($_SERVER['HTTP_REFERER'])){
  header('location:index.php');
  exit;
}

$secret = '6LcKYgYdAAAAAF2GLA1FkoET6MH_3hGN4rLrjodB';
$SENDER_EMAIL = 'ictteamaas@gmail.com';
$SENDER_PASSWORD = 'noqskyyrjtimpunj';

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
  ->setUsername('ictteamaas@gmail.com')
  ->setPassword('noqskyyrjtimpunj');

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

function sendForgetPasswordEmail($email){
  $dbObj = new Database();
  $conn = $dbObj->connection();
  $error ='';

  date_default_timezone_set('Asia/Singapore');
  $curFormat = mktime(date("H"), date("i"), date("s"), date("m") ,date("d"), date("Y"));
  $expFormat = mktime(date("H"), date("i")+15, date("s"), date("m") ,date("d"), date("Y"));
  $expDate = date("Y-m-d H:i:s",$expFormat);
  $curDate = date("Y-m-d H:i:s",$curFormat);
  $key = bin2hex(openssl_random_pseudo_bytes(16));

  $sql = $conn->prepare("SELECT * FROM password_reset_temp WHERE email = ?");
  $sql->bind_param("s", $email);
  $sql->execute();
  $result = $sql->get_result();
  if ($result->num_rows > 0) {
      while ($reset = $result->fetch_assoc()) {
        $dbdate = $reset['expDate'];
    }
    
    $hourdiff = round((strtotime($curDate) - strtotime($dbdate))/3600, 1);
    if ($hourdiff > 24)
    {
      $stmt = $conn->prepare("UPDATE password_reset_temp SET keyValue=? expDate=? WHERE email=?");
      $stmt->bind_param('sss', $key, $expDate, $email);
      $stmt->execute();
    }

    else
    {
      $error = "You can only request to change your password every 24 hours.";
    }
  }

  else{
    $stmt = $conn->prepare("INSERT INTO password_reset_temp (email, keyValue, expDate) VALUES (?,?,?)");
    $stmt->bind_param("sss", $email, $key, $expDate);
    $stmt->execute();
    $log = "Reset password request for ".$email;
    logger($log);
  }

  if ($error =='')
  {
    $length = 15;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    $email = $_POST['email'];
    $code = $randomString;
    global $mailer;
    
    $body = '<!DOCTYPE html>
      <html lang="en">
      <head>
        <meta charset="UTF-8">
        <title>Test mail</title>
        <style>
          .wrapper {
            padding: 20px;
            color: #444;
            font-size: 1.3em;
          }
          a {
            background: #592f80;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            color: #fff;
          }
        </style>
      </head>
      <body>
        <div class="wrapper">
          <p>Dear User</p>
          <p>Please click on the following link to reset your password.</p>
          <p>-------------------------------------------------------------</p>
          <p>https://aas.sitict.net/reset-password.php?key='.$key.'&email='.$email.'&action=reset</p>
          <p>-------------------------------------------------------------</p>
          <p>Please be sure to copy the entire link into your browser. The link will expire after 15mins for security reasons.</p>
          <p>If you did not request this forgotten password email, no action is needed, your password will not be reset. However, you 
          may want to log into your account and change your security password as someone may have guessed it.</p>
        </div>
      </body>
      </html>';

    // Create a message
    $message = (new Swift_Message('Forget Password'))
      ->setFrom('ictteamaas@gmail.com')
      ->setTo($email)
      ->setBody($body, 'text/html');

    // Send the message
    $result = $mailer->send($message);
  }
  return $error;
}

function sendVerificationEmail($userEmail, $token)
{
    global $mailer;
    $body = '<!DOCTYPE html>
      <html lang="en">
      <head>
        <meta charset="UTF-8">
        <title>Test mail</title>
        <style>
          .wrapper {
            padding: 20px;
            color: #444;
            font-size: 1.3em;
          }
          a {
            background: #592f80;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            color: #fff;
          }
        </style>
      </head>
      <body>
        <div class="wrapper">
          <p>Thank you for signing up on our site. Please click on the link below to verify your account:.</p>
          <a href="https://aas.sitict.net/verify_email.php?token=' . $token . '">Verify Email!</a>
        </div>
      </body>
    </html>';

    // Create a message
    $message = (new Swift_Message('Verify your email'))
      ->setFrom('ictteamaas@gmail.com')
      ->setTo($userEmail)
      ->setBody($body, 'text/html');

    // Send the message
    $result = $mailer->send($message);

    if ($result > 0) {
      return true;
    } else {
      return false;
  }
}

function updateProfileEmailNotification($userEmail)
{
    global $mailer;
    date_default_timezone_set('Asia/Singapore');
    $date = date("d-m-Y");
    $time =  date("h:i:sa");

    $body = '<!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Change in AAS Account Information</title>
      <style>
        .wrapper {
          padding: 20px;
          color: #444;
          font-size: 1.3em;
        }
      </style>
    </head>
    <body>
      <div class="wrapper">
      <h2>Your account information has recently been changed on ' . $date . ' at ' . $time . '</h2>
      <p>If this is not authorised by you, please contact us at ictteamaas@gmail.com or +6591231234. If this is you, please ignore this email. </p>
      </div>
    </body>
    </html>';

    // Create a message
    $message = (new Swift_Message('Account Information Changed'))
      ->setFrom('ictteamaas@gmail.com')
      ->setTo($userEmail)
      ->setBody($body, 'text/html');

    // Send the message
    $result = $mailer->send($message);

    if ($result > 0) {
      return true;
    } else {
      return false;
  }
}