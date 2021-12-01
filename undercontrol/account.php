<?php
class Account
{
    public $fullname;
    public $username;
    public $email;
    public $user_privilege;

    function __construct($fullname, $username, $email, $user_privilege)
    {
        $this->fullname = $fullname;
        $this->username = $username;
        $this->email = $email;
        $this->user_privilege = $user_privilege;
    }
}
?>