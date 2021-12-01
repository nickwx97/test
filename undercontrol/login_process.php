<?php
session_start();
if (isset($_SESSION['is_logged_in'])) {
	// Block users from going otp should they be logged in
	if ($_SESSION['is_logged_in'] == true) {
		header('Location: dashboard');
	}
}
require_once('logging.php');
require_once('db_mapper_factory.php');
require_once('account.php');
require_once('login.php');
require_once('validation_input.php');
require('totp.php');
require_once('smtp_process.php');

if (isset($_POST['action'])) {
	if ($_POST['action'] === 'login_account') {
		$message = "default";
		$loginEmail = sanitize_input($_POST['email']);
		$loginPass = sanitize_input($_POST['password']);

		if (is_email_valid($loginEmail) and is_password_valid($loginPass)) {
			$dbMapperFactory = new DBMapperFactory();
			$login_mapper_instance = $dbMapperFactory->createMapperInstance("Login");
			$account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");
			$log_conn = $login_mapper_instance->readFile();
			$acc_conn = $account_mapper_instance->readFile();

			//new connection to db
			//retrieve acc id
			$tempid = $account_mapper_instance->readIDFromDBbyUsername($acc_conn, "Account", $loginEmail);
			$temppass = $login_mapper_instance->readpassFromDBbyID($log_conn, "Login", $tempid);

			// $password = password_hash($loginPass, PASSWORD_BCRYPT);
			$acc_conn = $account_mapper_instance->readFile();
			$account_details = $account_mapper_instance->readRowByIDFromDB($acc_conn, "Account", $tempid);

			$_SESSION['user_id'] = $account_details['account_id'];
			$_SESSION['fullname'] = $account_details['account_fullname'];
			$_SESSION['username'] = $account_details['account_username'];
			$_SESSION['email'] = $account_details['account_email'];
			$_SESSION['user_privilege'] = $account_details['user_privilege'];

			if (!isset($_SESSION['LAST_ACTIVITY_UPDATE'])) {
				$_SESSION['LAST_ACTIVITY_UPDATE'] = time();
			}

			// If previous entries are still stored in db, delete first
			deleteOTP($_SESSION['user_id']);

			$success = generateSecretKey($_SESSION['user_id']);

			$message = "";
			logEvent($tempid, "Success", "Login authentication success!");
			// If password is successful, get the password ready
			// if (password_verify($loginPass, $temppass)) {
			// 	$acc_conn = $account_mapper_instance->readFile();
			// 	$account_details = $account_mapper_instance->readRowByIDFromDB($acc_conn, "Account", $tempid);

			// 	$_SESSION['user_id'] = $account_details['account_id'];
			// 	$_SESSION['fullname'] = $account_details['account_fullname'];
			// 	$_SESSION['username'] = $account_details['account_username'];
			// 	$_SESSION['email'] = $account_details['account_email'];
			// 	$_SESSION['user_privilege'] = $account_details['user_privilege'];

			// 	if (!isset($_SESSION['LAST_ACTIVITY_UPDATE'])) {
			// 		$_SESSION['LAST_ACTIVITY_UPDATE'] = time();
			// 	}

			// 	// If previous entries are still stored in db, delete first
			// 	deleteOTP($_SESSION['user_id']);

			// 	$success = generateSecretKey($_SESSION['user_id']);

			// 	$message = "";

			// 	// Log for normal login success
			// 	logEvent($tempid, "Success", "Login authentication success!");
			// } else {
			// 	$success = false;
			// 	$message = "Login authentication failure, please try again!";
			// 	// Log for normal login fail
			// 	logEvent($tempid, "Failure", $message);
			// }
		} else {
			$success = false;
			$message = "Login authentication failure, please try again!";
			// Log for normal login fail
			logEvent($tempid, "Failure", $message);
		}

		$json_array = array(
			// 'login_pass' => $loginPass,
			'message' => $message,
			// 'password' =>  $temppass,
			'success' => $success,

		);
		echo json_encode($json_array);
	}
	if ($_POST['action'] === 'submitOTP') {
		// Increment session count of OTP attempts should implementation fails

		// Retrieve the signal if OTP is successfully entered or not
		$otpEntered = sanitize_input($_POST['OTP']);

		if (is_otp_valid($otpEntered)) {
			if (authenticateOTP($otpEntered, $_SESSION['user_id']) == false) {
				$is_otp_max_attempts = false;
				$message = "Incorrect OTP entered!";
				$success = false;
				// Log for otp fail
				logEvent($_SESSION['user_id'], "Failure", $message);

			} else {
				$_SESSION['is_logged_in'] = true;
				$_SESSION['regenOTPcount'] = 0;
				$message = "You have successfully logged in!";
				$success = true;
				// Log for otp success
				logEvent($_SESSION['user_id'], "Success", $message);
			}
		}

		$json_array = array(
			'message' => $message,
			'success' => $success
		);
		echo json_encode($json_array);
	}
	if ($_POST['action'] === 'genNewOTP') {
		$newgenid = $_POST['newgenID'];
		$_SESSION['regenOTPcount'] = $_SESSION['regenOTPcount'] + 1;

		if ($_SESSION['regenOTPcount'] < 6) {
			deleteOTP($newgenid);
			$success = generateSecretKey($newgenid);
			if ($success) {
				// Log for OTP generated
				logEvent($_SESSION['user_id'], "Success", "A new OTP has been successfully generated");
				$message = "A new OTP has been successfully generated, please check your email!";
			} else {
				logEvent($_SESSION['user_id'], "Failure", "Failed to generate new OTP");
				$message = "Failed to generate new OTP, please try again!";
			}
		}
		$json_array = array(
			'message' => $message,
			'number_of_otp'=> $_SESSION['regenOTPcount'],
			'success' => $success,
		);
		echo json_encode($json_array);
	}
}

function generateSecretKey($acc_id)
{
	date_default_timezone_set('Asia/Singapore');
	$dbMapperFactory = new DBMapperFactory();
	$login_mapper_instance = $dbMapperFactory->createMapperInstance("Login");
	$log_conn = $login_mapper_instance->readFile();

	$information = $login_mapper_instance->readSecretKeyFromDBbyID($log_conn, "Login", $acc_id);

	$secret = $information;
	$time = time();
	//concat unix time and secret only if username and password matc
	$combinetext = $secret . $time;
	//store otp into database
	//60 char string
	$startpoint = rand(0, 54);
	$hashstring =  hash('sha256', $combinetext);
	$otp = substr($hashstring, $startpoint, 6);

	$expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));

	$account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");
	$account_conn = $account_mapper_instance->readFile();
	$verifyExists = $account_mapper_instance->checkAccountExistance($account_conn, "Account", $acc_id);

	if ($verifyExists) {
		$totp = new TOTP($acc_id, $expiry, $otp);

		$TOTP_mapper_instance = $dbMapperFactory->createMapperInstance("TOTP");
		$TOTP_conn = $TOTP_mapper_instance->readFile();

		$information = $TOTP_mapper_instance->insertRowToDB($TOTP_conn, "TOTP", $totp);

		$result = generateEmail($otp);

	} else {
		$result = false;
	}

	return $result;
}

function generateSecretKeyForTest($acc_id)
{
	$dbMapperFactory = new DBMapperFactory();
	$account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");
	$account_conn = $account_mapper_instance->readFile();
	$verifyExists = $account_mapper_instance->checkAccountExistance($account_conn, "Account", $acc_id);
	return $verifyExists;
}

function checkIfEligibleToGenerateOTP($otp_generation_number)
{
	$otp_generation_number = $otp_generation_number + 1;
	if ($otp_generation_number < 6) {
		return true;
	} else {
		return false;
	}
}

function generateEmail($otp)
{
	$senderName = "FireAway";
	$senderEmail = 'fireaway3001@gmail.com';
	$recipientName = $_SESSION['fullname'];
	$recipientEmail = $_SESSION['email'];
	$subjectSender = "Generate OTP";
	$feedbackMessage = "<h1> Your OTP is: " . $otp . "</h1>";
	$email = new Email($senderName, $senderEmail, $recipientName, $recipientEmail, $subjectSender, $feedbackMessage);
	$result = sendEmail($email);
	return $result;
}

function authenticateOTP($OTP, $acc_id)
{
	//function to get otp by id from database
	$dbMapperFactory = new DBMapperFactory();
	$totp_mapper_instance = $dbMapperFactory->createMapperInstance("TOTP");
	$totp_conn = $totp_mapper_instance->readFile();

	$information = $totp_mapper_instance->readOTPByAccountIDFromDB($totp_conn, "TOTP", $acc_id);

	//compare input to database
	if ($OTP != $information) {
		return false;
	} else {
		// create a function to delete otp on success
		deleteOTP($_SESSION['user_id']);
		return true;
	}
}

function deleteOTP($ID)
{
	$dbMapperFactory = new DBMapperFactory();
	$totp_mapper_instance = $dbMapperFactory->createMapperInstance("TOTP");
	$totp_conn = $totp_mapper_instance->readFile();

	$totp_mapper_instance->deleteRowFromDB($totp_conn, "TOTP", $ID);
}
?>
