<?php
class Login
{
    public $account_id;
    public $passwordVerifier;
    public $smallSalt;

    function __construct($account_id, $passwordVerifier, $smallSalt)
    {
        $this->account_id = $account_id;
        $this->passwordVerifier = $passwordVerifier;
        $this->smallSalt = $smallSalt;
    }
}
?>
