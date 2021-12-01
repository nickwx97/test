<?php
session_start();
require_once "vendor/autoload.php";
include_once "class/Otp.php";

if(!isset($_SESSION["temp_user_id"])){
	die();
}

$otpObj = new OTPCheck($_SESSION["temp_user_id"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['otp'])){
		$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
		if ($_POST['otp'] == $g->getCode($_SESSION['secret'])){
			if ($otpObj->storeSecret($_SESSION['secret'])){
				session_destroy();
				header("Location:login.php");
			}else {
				header("Location:2fa.php");
			}
		} else {
			header("Location:2fa.php");
		}
	}
}
die();