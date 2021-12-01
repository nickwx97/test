<?php 
if (!isset($_SESSION)) {
	session_start();
}

require_once('validation_input.php');
require('email.php');
require_once('logging.php');

use PHPMailer\PHPMailer\PHPMailer;

// All these are required from the 3rd Party PHPMailer repository
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'submitRespond') {
        $senderName = sanitize_input($_POST['senderName']);
        $senderEmail = sanitize_input($_POST['senderEmail']);
        $recipientName = sanitize_input($_POST['recipientName']);
        $recipientEmail = sanitize_input($_POST['recipientEmail']);
        $subjectSender = sanitize_input($_POST['subjectSender']);
        $feedbackMessage = sanitize_input($_POST['feedbackMessage']);

        // Do PHP validation first before sending email
        if (is_fullname_valid($senderName) and is_email_valid($senderEmail) and is_fullname_valid($recipientName) and is_email_valid($recipientEmail) and is_subject_valid($subjectSender) and is_message_valid($feedbackMessage)) {
            $email = new Email($senderName, $senderEmail, $recipientName, $recipientEmail, $subjectSender, $feedbackMessage);
            $result = sendEmail($email);
            if ($result) {
                logEvent($_SESSION['user_id'], "Success", "Feedback Response sent");
            } else {
                logEvent($_SESSION['user_id'], "Failure", "Failed to respond feedback");
            }
        } else {
            // Always fails if PHP validation did not pass
            $result = false;
            logEvent($_SESSION['user_id'], "Failure", "Failed to respond feedback");
        }

        //Just checking for passing will get a succes to for the email to pass in when api is up
        $json_array = array(
            'senderEmail' => $senderEmail,
            'recipientEmail' => $recipientEmail,
            'subjectSender' => $subjectSender,
            'feedbackMessage' =>  $feedbackMessage,
            'success' => $result
        );
        echo json_encode($json_array);
    }
}

function sendEmail(object $email) {
    // Create new instance of PHP Mailer
    $php_mailer = new PHPMailer();

    // Set mailer to use SMTP
    $php_mailer->isSMTP();

    // Main SMTP server which is gmail
    $php_mailer->Host = 'smtp.gmail.com';

    // Enable SMTP authentication
    $php_mailer->SMTPAuth = true;

    // Enable TLS encryption
    $php_mailer->SMTPSecure = 'tls';

    // TCP port to connect to
    $php_mailer->Port = 587;

    // Set sender email first
    $php_mailer->Username = $email->senderEmail;

    // Specify Password
    $php_mailer->Password = 'Fire@away123';

    // Set sender info
    $php_mailer->SetFrom($email->senderEmail, $email->senderName);

    // Set recipient email and name
    $php_mailer->addAddress($email->recipientEmail, $email->recipientName);

    $php_mailer->addReplyTo('no-reply@fireaway.com', 'Information FireAway');

    $php_mailer->isHTML(true);

    // Specify the subject
    $php_mailer->Subject = $email->subject;

    // Specify the feedback content
    $php_mailer->Body = $email->message;

    // Retrieve the email statuses after sending the mail
    $result = $php_mailer->Send();

    return $result;
}
?>
