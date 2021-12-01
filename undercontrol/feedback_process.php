<?php
if (!isset($_SESSION)) {
    session_start(); 
}
require_once('logging.php');
require_once('db_mapper_factory.php');
require('feedback.php');
require_once('validation_input.php');

if (isset($_POST['action'])) {

    if ($_POST['action'] === 'submitFeedback') {
        // get the object
        $dbMapperFactory = new DBMapperFactory();
        $feedback_mapper_instance = $dbMapperFactory->createMapperInstance("Feedback");

        $conn = $feedback_mapper_instance->readFile();

        $subject = sanitize_input($_POST['subject']);
        $fullname = sanitize_input($_POST['fullname']);
        $ddCountryCode = sanitize_input($_POST['ddCountryCode']);
        $mobile_no = sanitize_input($_POST['mobile_no']);
        $email = sanitize_input($_POST['email']);
        $ddFeedbackType = sanitize_input($_POST['ddFeedbackType']);
        $message = sanitize_input($_POST['message']);
        
        // Set default timezone to Singapore (GMT +8)
        date_default_timezone_set('Asia/Singapore');

        $current_datetime = date('Y-m-d');
        $limitCountEmail = $feedback_mapper_instance->readCountLimitFromDB($conn, "Feedback",$email,$current_datetime); // limit the insert to 20
        $new_feedback = new Feedback($subject, $fullname, $ddCountryCode, $mobile_no, $email, $ddFeedbackType, $message);

        if (is_subject_valid($subject) and is_fullname_valid($fullname) and is_mobile_valid($mobile_no) and is_email_valid($email) and is_message_valid($message) and $limitCountEmail <=20) {
            // Returns boolean success depending on whether query is successful
            $feedback_mapper_instance = $dbMapperFactory->createMapperInstance("Feedback");
            $conn = $feedback_mapper_instance->readFile();
            $success = $feedback_mapper_instance->insertRowToDB($conn, "Feedback", $new_feedback);
        } else {
            // Always returns false should PHP validation fails
            $success = false;
        }

        // might need to edit
        $json_array = array(
            'success' => $success,
            'count'=> $limitCountEmail
        );
        echo json_encode($json_array);
    }

    if ($_POST['action'] === 'deleteFeedback') {
        // get the post to delete
        $deleteID = $_POST['deleteID'];

        // get the object feedback
        $dbMapperFactory = new DBMapperFactory();
        $feedback_mapper_instance = $dbMapperFactory->createMapperInstance("Feedback");
        $conn = $feedback_mapper_instance->readFile();
        $success = $feedback_mapper_instance->deleteRowFromDB($conn, "Feedback", $deleteID);

        if ($success) {
            logEvent($_SESSION['user_id'], "Success", "Deleted a Feedback");
        } else {
            logEvent($_SESSION['user_id'], "Failure", "Failed to delete Feedback");
        }
        $json_array = array(
            'success' => $success
        );
        echo json_encode($json_array);
    }
}

function check_feedback_eligiblity($feedback_count) {
    // this test is to simulate if users can enter a feedback entry with if they are currently less than 20 entries daily
    if ($feedback_count < 20) {
        return true;
    } else {
        return false;
    }
}
?>
