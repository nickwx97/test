<?php
include_once "database.php";
class OTPCheck
{
    // Properties
    public $dbObj;
    public $con;
    public $user_id;
    public $verified;

    // Methods
    public function __construct($user_id)
    {
        $this->dbObj = new Database();
        $this->con = $this->dbObj->connection();
        $this->user_id = $this->con->real_escape_string($user_id);
        $this->verified = 0;
    }

    public function insertNew(){
        $sql = $this->con->prepare("INSERT INTO `otp` (`user_id`, `generated`) VALUES(?, 0)");
        $sql->bind_param("i", $this->user_id);
        $sql->execute();
        $result = $sql->get_result();
         
        if ($sql == true) {
            return true;
        } else {
            return false;
        }
    }

    public function checkExists(){
        $sql = $this->con->prepare("SELECT * FROM `otp` WHERE `user_id` = ?");
        $sql->bind_param("i", $this->user_id);
        $sql->execute();
        $result = $sql->get_result();
        if ($result->num_rows == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function checkGenerated(){
        $sql = $this->con->prepare("SELECT * FROM `otp` WHERE `user_id` = ?");
        $sql->bind_param("i", $this->user_id);
        $sql->execute();
        $result = $sql->get_result();

        while ($student_data = $result->fetch_assoc()) {
            $generated = $student_data['generated'];
        }

        if ($generated == 1) {
            return true;
        } else if ($generated == 0) {
            return false;
        }
    }

    public function storeSecret($secret){
        $sql = $this->con->prepare("UPDATE `otp` SET `secret` = ?, `generated` = 1 WHERE `user_id` = ? AND `secret` IS NULL");
        $sql->bind_param("si", $secret, $this->user_id);
        $sql->execute();
        if ($sql == true) {
            return true;
        } else {
            return false;
        }
    }

    public function verifyOTP($otp){

        $sql = $this->con->prepare("SELECT * FROM `otp` WHERE `user_id` = ?");
        $sql->bind_param("i", $this->user_id);
        $sql->execute();
        $result = $sql->get_result();

        while ($student_data = $result->fetch_assoc()) {
            $secret = $student_data['secret'];
        }

        $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        if($g->checkCode($secret, $otp)){
            return true;
        }else{
            return false;
        }
    }

}
