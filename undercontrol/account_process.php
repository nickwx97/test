<?php
session_start();
require_once('db_mapper_factory.php');
require_once('account.php');
require_once('login.php');
require_once('validation_input.php');
require_once('smtp_process.php');
require_once('logging.php');
require('SRPfunction.php');

$username = $password = $secret = "";
$successFlag = false;

if (isset($_POST['action'])) {

    if ($_POST['action'] === 'register_account') {
        // get the object
        $dbMapperFactory = new DBMapperFactory();

        $fullname = sanitize_input($_POST['fullname']);
        $username = sanitize_input($_POST['username']);
        $email = sanitize_input($_POST['email']);
        $new_user_privilege = sanitize_input($_POST['user_privilege']);

        $current_user_privilege = $_SESSION['user_privilege'];

        $password = sanitize_input($_POST["password"]);

        if (is_fullname_valid($fullname) and is_username_valid($username) and is_email_valid($email) and is_password_valid($password)) {
            $account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");
            $acc_conn = $account_mapper_instance->readFile();
            $success = $account_mapper_instance->verifyCreateAccDuplication($acc_conn, "Account", $username, $email);

            if ($success) {
                if ($current_user_privilege == 2) {
                    // return PasswordVerifier & Salt
                    // [0] = PasswordVerifier
                    // [1] = Salt
                    $getPassVerifier = registerSRP($email, $password);

                    $acc_conn = $account_mapper_instance->readFile();        
                    $new_account = new Account($fullname, $username, $email, $new_user_privilege);
                    // Returns boolean success depending on whether query is successful
                    $success = $account_mapper_instance->insertRowToDB($acc_conn, "Account", $new_account);

                    // // hashing password
                    // $password = password_hash($password, PASSWORD_BCRYPT);
                    // // create cryptographically secure pseudo-random bytes to be used for TOTP
                    // $secret = random_bytes(64);
                    // $secret = bin2hex($secret);
    
                    $acc_conn = $account_mapper_instance->readFile();
                    $new_account_id = $account_mapper_instance->readLatestAccountByIDFromDB($acc_conn, "Account");
    
                    $login_mapper_instance = $dbMapperFactory->createMapperInstance("Login");
                    $login_conn = $login_mapper_instance->readFile();
                    $new_login = new Login($new_account_id, $getPassVerifier[0], $getPassVerifier[1]);
                    $success = $login_mapper_instance->insertRowToDB($login_conn, "Login", $new_login);
    
                    if ($success) {
                        sendRegisteredAccountSMTP($_POST['fullname'], $_POST['email']);
                        logEvent($_SESSION['user_id'], "Success", "Added account for login");
                    }
                } else {
                    $success = false;
                    logEvent($_SESSION['user_id'], "Failure", "Fail to add account");
                }
            }
        } else {
            // Always returns false should PHP validation fails
            $success = false;
            logEvent($_SESSION['user_id'], "Failure", "Fail to add account");
        }

        // might need to edit
        $json_array = array(
            'password'=> $password,
            'success' => $success,
        );
        echo json_encode($json_array);
    }

    if ($_POST['action'] === 'deleteAccount') {
        // get the post to delete
        $deleteID = sanitize_input($_POST['deleteID']);
        $user_privilege = $_SESSION['user_privilege'];

        if ($deleteID == $_SESSION['user_id']) {
            $success = false;
        } else {
            if ($user_privilege == 2) {
                // get the object feedback
                $dbMapperFactory = new DBMapperFactory();

                // Delete login entry first
                $login_mapper_instance = $dbMapperFactory->createMapperInstance("Login");
                $login_conn = $login_mapper_instance->readFile();
                $success = $login_mapper_instance->deleteRowFromDB($login_conn, "Login", $deleteID);

                // If login entry deleted successfully, delete actual account entry
                if ($success) {
                    $account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");
                    $acc_conn = $account_mapper_instance->readFile();
                    $success = $account_mapper_instance->deleteRowFromDB($acc_conn, "Account", $deleteID);
                    logEvent($_SESSION['user_id'], "Success", "Deleted account");
                }   
            } else {
                $success = false;
                logEvent($_SESSION['user_id'], "Failure", "Failed to delete account");
            }
        }

        $json_array = array(
            'success' => $success
        );
        echo json_encode($json_array);
    }
}

function sendRegisteredAccountSMTP($fullname, $email) {
    $senderName = "FireAway";
	$senderEmail = 'fireaway3001@gmail.com';
	$recipientName = $fullname;
	$recipientEmail = $email;
	$subjectSender = "Fireaway Account Registration";
    $url = "https://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/login_account.php";
	$feedbackMessage = "Your FireAway account has been created, please login through the <a href='$url'>LINK</a> provided!";
	$email = new Email($senderName, $senderEmail, $recipientName, $recipientEmail, $subjectSender, $feedbackMessage);
    sendEmail($email);
}

function test_add_employee($user_id, $add_account_info) {
    // this test is to simulate if employees are allowed addition of employees (by right no)
    if ($_SESSION['user_privilege'] == 2) {
        return true;
    } else {
        return false;
    }
}

function test_delete_employee($user_id) {
    // this test is to simulate if managers are allowed deletion of employees (by right no)
    if ($_SESSION['user_privilege'] == 2) {
        return true;
    } else {
        return false;
    }
}
?>
