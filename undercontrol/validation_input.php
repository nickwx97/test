<?php
/* Remove any illegal characters to minimise SQL injection & XSS attacks */
function sanitize_input($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

/* Essential to do back-end PHP validations as well in case Javascript front-end
    validations have been bypassed unintentionally before inserting to the database */
function is_fullname_valid($input)
{
    if (!isset($input) or empty($input) or !preg_match("/^[a-zA-Z ,.'-]{1,200}$/", $input)) {
        return false;
    } else {
        return true;
    }
}

function is_username_valid($input)
{
    if (!isset($input) or empty($input) or !preg_match("/^[a-zA-Z0-9_]{6,20}$/", $input)) {
        return false;
    } else {
        return true;
    }
}

function is_email_valid($input)
{
    if (!isset($input) or empty($input) or !filter_var($input, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        return true;
    }
}

function is_password_valid($input)
{
    $password_valid = true;
    if (!isset($input) or empty($input) or !preg_match("/^[a-zA-Z0-9_]{8,16}$/", $input)) {
        $password_valid = false;
    } else {
        $weak_password_string = file_get_contents("password/weak_password.txt");
        $weak_password_string = explode("\n", $weak_password_string);
        if (in_array($input, $weak_password_string)) {
            $password_valid = false;
        } else {
            $password_valid = true;
        }
    }
    return $password_valid;
}

// From Feedback Form
function is_subject_valid($input)
{
    if (!isset($input) or empty($input) or !preg_match("/^.{1,200}$/", $input)) {
        return false;
    } else {
        return true;
    }
}

// From Feedback Form
function is_mobile_valid($input)
{
    if (!isset($input) or empty($input) or !preg_match("/^[0-9]{8,10}$/", $input)) {
        return false;
    } else {
        return true;
    }
}

// From Feedback Form
function is_message_valid($input)
{
    // Recommended feedback character limit: https://support.lonelyplanet.com/hc/en-us/articles/218158527-Why-do-you-have-a-2000-character-limit-in-the-feedback-form
    if (!isset($input) or empty($input) or !preg_match("/^.{1,2000}$/", $input)) {
        return false;
    } else {
        return true;
    }
}

function is_otp_valid($input)
{
    if (!isset($input) or empty($input) or !preg_match("/^[a-zA-Z0-9]{6}$/", $input)) {
        return false;
    } else {
        return true;
    }
}

// Testing function to generate random string with length specified
function generateRandomString($length)
{
    $valid_characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ,./!@#$%^&*()\s-=';
    $charactersLength = strlen($valid_characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $valid_characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
