<?php
session_start();
include "logging.php";
require_once "vendor/autoload.php";
include_once "class/Otp.php";
include_once "class/UserManagement.php";

if(!isset($_SESSION["temp_user_id"])){
	die();
}

$userManagementObj = new UserManagement();
$otpObj = new OTPCheck($_SESSION["temp_user_id"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['otp'])){
		if ($otpObj->verifyOTP($_POST['otp'])){
			$newdata['email'] = $_SESSION['email'];
    		$newdata['password'] = $_SESSION['password'];
			$userManagementObj->login($newdata);
			if ($_SESSION['role'] == 'admin') {
				$log = "Successful admin login";
				logger($log);
				header("Location:admin.php");
			} else if ($_SESSION['role'] == 'employee') {
				$log = "Successful employee login";
				logger($log);
				header("Location:employee.php");
			} else {
				$log = "Successful user login";
				logger($log);
				header("Location:services.php");
			}
		}else {
			session_destroy();
			header("Location: login.php");
		}
	}
}
die();