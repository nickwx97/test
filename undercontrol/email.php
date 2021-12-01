<?php
class Email
{
    public $senderName;
    public $senderEmail;
    public $recipientName;
    public $recipientEmail;
    public $subject;
    public $message;

    function __construct($senderName, $senderEmail, $recipientName, $recipientEmail, $subject, $message)
    {
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
        $this->recipientName = $recipientName;
        $this->recipientEmail = $recipientEmail;
        $this->subject = $subject;
        $this->message = $message;
    }
}
?>
