<?php
session_start(); 
require('db_mapper_factory.php');
require('account.php');
require('login.php');
require('SRPfunction.php');
require_once('validation_input.php');

$password = "";

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'submitNewPassword') {
        // get the object
        $dbMapperFactory = new DBMapperFactory();

        $token_mapper_instance = $dbMapperFactory->createMapperInstance("Token");
        $token_conn =  $token_mapper_instance->readFile();

        $login_mapper_instance = $dbMapperFactory->createMapperInstance("Login");
        $login_conn = $login_mapper_instance->readFile();

        $account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");
        $acc_conn = $account_mapper_instance->readFile();

        $pass1 = sanitize_input($_POST["resetPass1"]);
        $pass2 = sanitize_input($_POST["resetPass2"]);
        $token = sanitize_input($_POST["token"]);

        $getAccId = $token_mapper_instance->getIDFromToken($token_conn, "Token", $token);
        $accID = $getAccId->fetch_object()->account_id;

        $getAccEmail = $account_mapper_instance->getEmailFromDBbyID($acc_conn, "Account", $accID);

        $accEmail = $getAccEmail->fetch_object()->account_email;

        if (is_password_valid($pass1) and is_password_valid($pass2)) {
            if ($pass1 == $pass2) {
                $password =  $pass1;
                $getPassVerifier = registerSRP($accEmail, $password);

                $success = $login_mapper_instance->updatePassword($login_conn, "Login", $getPassVerifier[0], $getPassVerifier[1], $accID);
                
                if ($success == true) {
                    $token_conn =  $token_mapper_instance->readFile();
                    $success = $token_mapper_instance->deleteRowFromDB($token_conn, "Token", $accID);
                    $failPassMsg = "";
                }
            } else {
                $success = false;
                $failPassMsg = "Passwords Invalid";
            }
        } else {
            // Always returns false should PHP validation fails
            $success = false;
            $failPassMsg = "Passwords Invalid";
        }

        // mmight need to edit
        $json_array = array(
            'failPassMsg' => $failPassMsg,
            'success' => $success
        );

        echo json_encode($json_array);
    }
}
?>