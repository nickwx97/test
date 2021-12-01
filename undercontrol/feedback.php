<?php
class Feedback
{
    public $feedback_subject;
    public $public_fullname;
    public $public_country_code;
    public $public_mobile_no;
    public $public_email;
    public $feedback_type;
    public $feedback_content;

    function __construct($feedback_subject, $public_fullname, $public_country_code, $public_mobile_no, $public_email, $feedback_type, $feedback_content)
    {
        $this->feedback_subject = $feedback_subject;
        $this->public_fullname = $public_fullname;
        $this->public_country_code = $public_country_code;
        $this->public_mobile_no = $public_mobile_no;
        $this->public_email = $public_email;
        $this->feedback_type = $feedback_type;
        $this->feedback_content = $feedback_content;
    }
}
?>