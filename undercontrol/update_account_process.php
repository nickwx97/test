<?php
session_start();
require_once('db_mapper_factory.php');
require_once('account.php');
require_once('validation_input.php');

if (isset($_POST['action'])) {

    if ($_POST['action'] === 'submitUpdateInfo') {
        // get the object
        $dbMapperFactory = new DBMapperFactory();
        $account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");

        $conn = $account_mapper_instance->readFile();

        $accountID = ($_POST['accountID']);
        $fullname = sanitize_input($_POST["updateFullname"]);
        $username = sanitize_input($_POST["updateUserName"]);
        $email = sanitize_input($_POST["updateEmail"]);
        $user_privilege = sanitize_input($_POST["updateType"]);

        $update_accountInfo = new Account($fullname, $username, $email, $user_privilege);

        if (is_fullname_valid($fullname) and is_username_valid($username) and is_email_valid($email)) {
            $success = $account_mapper_instance->verifyUpdateAccDuplication($conn, "Account", $accountID, $username, $email);
            if ($success == true)
            {
                // Only managers permitted
                if ($_SESSION['user_privilege'] == 2) {
                    $conn = $account_mapper_instance->readFile();
                    // Returns boolean success depending on whether query is successful
                    $success = $account_mapper_instance->updateRowToDB($conn, "Account",  $accountID, $update_accountInfo);

                    if ($success) {
                        // Check if person updating its own details, update session as well
                        if ($_SESSION['user_id'] == $accountID) {
                            $_SESSION['fullname'] = $fullname;
                            $_SESSION['username'] = $username;
                            $_SESSION['email'] = $email;

                            // Retrieve user privilege instead of role directly, 1: Employee, 2: Manager
                            $_SESSION['user_privilege'] = $user_privilege;
                        }
                    }
                    $duplicatemsg = "";
                } else {
                    $success = false;
                }
            } else {
                $duplicatemsg = " invalid infromation";
            }
        } else {
            // Always returns false should PHP validation fails
            $success = false;
            $duplicatemsg = " error, failed to update!";
        }

        // mmight need to edit
        $json_array = array(
            'duplicatemsg' => $duplicatemsg,
            'success' => $success
        );

        echo json_encode($json_array);
    } if ($_POST['action'] === 'submitOwnInfo') {
        // get the object
        $dbMapperFactory = new DBMapperFactory();
        $account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");

        $conn = $account_mapper_instance->readFile();

        $accountID = sanitize_input($_POST['accountID']);
        $fullname = sanitize_input($_POST["updateFullname"]);
        $username = sanitize_input($_POST["updateUserName"]);
        $email = sanitize_input($_POST["updateEmail"]);
        $user_privilege = $_SESSION['user_privilege'];

        $update_accountInfo = new Account($fullname, $username, $email, $user_privilege);

        if (is_fullname_valid($fullname) and is_username_valid($username) and is_email_valid($email)) {
            $success = $account_mapper_instance->verifyUpdateAccDuplication($conn, "Account", $accountID, $username, $email);
            if ($success == true)
            {
                // safety net to check if user updates same account
                if ($_SESSION['user_id'] == $accountID) {
                    $conn = $account_mapper_instance->readFile();
                    // Returns boolean success depending on whether query is successful
                    $success = $account_mapper_instance->updateRowToDB($conn, "Account",  $accountID, $update_accountInfo);

                    if ($success) {
                        // Check if person updating its own details, update session as well
                        if ($_SESSION['user_id'] == $accountID) {
                            $_SESSION['fullname'] = $fullname;
                            $_SESSION['username'] = $username;
                            $_SESSION['email'] = $email;

                            // Retrieve user privilege instead of role directly, 1: Employee, 2: Manager
                            $_SESSION['user_privilege'] = $user_privilege;
                        }
                    }
                } else {
                    $success = false;
                }
                $duplicatemsg = "";
            } else {
                $duplicatemsg = "Invalid Infromation";
            }
        } else {
            // Always returns false should PHP validation fails
            $success = false;
            $duplicatemsg = " error, failed to update!";
        }

        // mmight need to edit
        $json_array = array(
            'duplicatemsg' => $duplicatemsg,
            'success' => $success
        );

        echo json_encode($json_array);
    }
}

function test_update_employee($user_id, $update_accountInfo) {
    // this test is to simulate if employees are allowed deletion of employees (by right no)
    if ($_SESSION['user_privilege'] == 2) {
        return true;
    } else {
        return false;
    }
}

function test_update_own_info($user_id, $update_accountInfo) {
    // this test is to simulate if users are allowed to update their own accounts (by right no)
    if ($_SESSION['user_id'] != $user_id) {
        return false;
    } else if ($_SESSION['user_privilege'] != $update_accountInfo->user_privilege) {
        return false;
    } else {
        return true;
    }
}
?>
