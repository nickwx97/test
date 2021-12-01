<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
if(!isset($_SESSION["user"])){
   $_SESSION["user"] = array(); 
}
header('Content-Type: application/json');

require_once('db_mapper_factory.php');
require_once('account.php');
require_once('login.php');
require 'srp.php';

if(!isset($_REQUEST["phase"])){
    echo json_encode(array("success" => false, "message" => "missing parameters"));
} elseif ($_REQUEST["phase"] == 1){
    //reveive I and A, search s, v by I in DB, generate b and B, send s, B to client
    $I = $_POST["I"];
    $A = $_POST["A"];

    // get the object
    $dbMapperFactory = new DBMapperFactory();

    // DB Connection
    $login_mapper_instance = $dbMapperFactory->createMapperInstance("Login");
    $account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");
    $log_conn = $login_mapper_instance->readFile();
    $acc_conn = $account_mapper_instance->readFile();

    // retrieve Acc ID
    $tempid = $account_mapper_instance->readIDFromDBbyUsername($acc_conn, "Account", $I);
    // retrieve PasswordVerifier
    $passVerify = $login_mapper_instance->readpassFromDBbyID($log_conn, "Login", $tempid);
    // retrieve salt
    $log_conn = $login_mapper_instance->readFile();
    $passSalt = $login_mapper_instance->readSecretKeyFromDBbyID($log_conn, "Login", $tempid);


    $_SESSION["user"][$I] = array("s" => $passSalt, "v" => $passVerify);
    
    if(!isset($_SESSION["user"][$I])){
        echo json_encode(array("success" => false));
        exit;
    }
    
    $_SESSION["s"] = $_SESSION["user"][$I]["s"];
    $_SESSION["v"] = $_SESSION["user"][$I]["v"];
    $_SESSION["A"] = $A;
    
    $srp = new srp();
    $_SESSION["b"] = $srp->getRandomSeed();
    $_SESSION["B"] = $srp->generateB($_SESSION["b"], $_SESSION["v"]);
    
    echo json_encode(array(
        "success" => true,
        "B" => $_SESSION["B"],
        "s" => $_SESSION["s"]
    ));
    
} elseif ($_REQUEST["phase"] == 2){
    //server receive M1, verify it, build k; send M2 back
    $M1 = $_POST["M1"];

    $srp = new srp();
    $_SESSION["S"] = $srp->generateS_Server($_SESSION["A"], $_SESSION["B"], $_SESSION["b"], $_SESSION["v"]);
    $M1_check = $srp->generateM1($_SESSION["A"], $_SESSION["B"], $_SESSION["S"]);
    
    if($M1 != $M1_check){
        echo json_encode(array("success" => false, "message" => $_SESSION["v"]));
        exit;
    } 
    $_SESSION["K"] = $srp->generateK($_SESSION["S"]);

    $M2 = $srp->generateM2($_SESSION["A"], $M1, $_SESSION["S"]);
    
    echo json_encode(array(
        "success" => true,
        "M2" => $M2
    ));
}