<?php
session_start();

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

require_once('db_mapper_factory.php');
require_once('account.php');
require_once('login.php');
require_once('validation_input.php');

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'checkEmailExists') {
        // get the object
        $dbMapperFactory = new DBMapperFactory();

        $account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");
        $acc_conn =  $account_mapper_instance->readFile();

        $getEmailToken = sanitize_input($_POST["getEmailToken"]);

        if (is_email_valid($getEmailToken)) {
            $success = $account_mapper_instance->verifyEmailExists($acc_conn, "Account", $getEmailToken);
            if ($success == true) {
                // get the object
                $account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");
                $acc_conn =  $account_mapper_instance->readFile();
                $token_mapper_instance = $dbMapperFactory->createMapperInstance("Token");
                $conn = $token_mapper_instance->readFile();
                // retrieve account ID via email
                $getAccID = $account_mapper_instance->getIDFromDBbyEmail($acc_conn, "Account", $getEmailToken);
                $accID = $getAccID->fetch_object()->account_id;

                // check if there is a existing token linked to an account ID
                $tokenExist = $token_mapper_instance->TokenExistence($conn, "Token", $accID);

                // if account ID has a existing token, delete the token and generate new one
                if ($tokenExist == true) {
                    $token_mapper_instance = $dbMapperFactory->createMapperInstance("Token");
                    $conn = $token_mapper_instance->readFile();
                    $success = $token_mapper_instance->deleteRowFromDB($conn, "Token", $accID);

                    $token = uniqid(true);
                    date_default_timezone_set("Asia/Singapore");
                    $expDate = date('Y-m-d H:i:s', strtotime("+10 minute"));
                    $token_mapper_instance = $dbMapperFactory->createMapperInstance("Token");
                    $conn = $token_mapper_instance->readFile();
                    $success = $token_mapper_instance->insertTokenCode($conn, "Token",  $accID, $token, $expDate);

                    //Create an instance; passing `true` enables exceptions
                    $mail = new PHPMailer(true);
                    try {
                        //Server settingst
                        $mail->isSMTP();                                          //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                 //Enable SMTP authentication
                        $mail->Username   = 'fireaway3001@gmail.com';               //Google Account username
                        $mail->Password   = 'Fire@away123';                   //Google Account password
                        $mail->SMTPSecure = 'tls';                                //Enable implicit TLS encryption
                        $mail->Port       = 587;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom('fireaway3001@gmail.com', 'FireAway');
                        $mail->addAddress("$getEmailToken");     //Add a recipient
                        $mail->addReplyTo('no-reply@fireaway.com', 'Information FireAway');

                        //Content
                        $url = "https://undercontrol.sitict.net/forget_pw_password?token=$token";
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'FireAway Password Reset Link';
                        $mail->Body    = "<h1>Password Reset Requested.</h1>
                                            Click this <a href='$url'>LINK</a> to reset password.";
                        $mail->send();
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                    $emailMsg = "";
                } else { // else if account ID has no token, generate new token
                    $token = uniqid(true);
                    date_default_timezone_set("Asia/Singapore");
                    $expDate = date('Y-m-d H:i:s', strtotime("+10 minute"));
                    $token_mapper_instance = $dbMapperFactory->createMapperInstance("Token");
                    $conn = $token_mapper_instance->readFile();
                    $success = $token_mapper_instance->insertTokenCode($conn, "Token",  $accID, $token, $expDate);

                    //Create an instance; passing `true` enables exceptions
                    $mail = new PHPMailer(true);
                    try {
                        //Server settings
                        $mail->isSMTP();                                          //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                 //Enable SMTP authentication
                        $mail->Username   = 'fireaway3001@gmail.com';               //Google Account username
                        $mail->Password   = 'Fire@away123';                   //Google Account password
                        $mail->SMTPSecure = 'tls';                                //Enable implicit TLS encryption
                        $mail->Port       = 587;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom('fireaway3001@gmail.com', 'FireAway');
                        $mail->addAddress("$getEmailToken");     //Add a recipient
                        $mail->addReplyTo('no-reply@fireaway.com', 'Information FireAway');

                        //Content
                        $url = $url = "https://undercontrol.sitict.net/forget_pw_password?token=$token";
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'FireAway Password Reset Link';
                        $mail->Body    = "<h1>Password Reset Requested.</h1>
                                            Click this <a href='$url'>LINK</a> to reset password.";
                        $mail->send();
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }

                    $emailMsg = "";
                }
            } else {
                $success = false;
                $emailMsg = "Invalid Email";
            }
        } else {
            // Always returns false should PHP validation fails
            $success = false;
            $emailMsg = "Invalid Email";
        }

        // mmight need to edit
        $json_array = array(
            'emailMsg' => $emailMsg,
            'success' => $success
        );

        echo json_encode($json_array);
    }
}

function generateTokenForTest($acc_id)
{
	$dbMapperFactory = new DBMapperFactory();
	$account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");
	$account_conn = $account_mapper_instance->readFile();
	$verifyExists = $account_mapper_instance->checkAccountExistance($account_conn, "Account", $acc_id);
	return $verifyExists;
}
?>
