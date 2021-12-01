<?php
class TOTP
{
    public $account_id;
    public $expiry_date;
    public $otp;

    function __construct($account_id, $expiry_date, $otp)
    {
        $this->account_id = $account_id;
        $this->expiry_date = $expiry_date;
        $this->otp = $otp;
    }
}
?>