<?php
require_once('logging.php');
if(!isset($_SESSION))
{
    session_start();
}
logEvent($_SESSION['user_id'], "Success", "Logout successful");
session_unset();
session_destroy();
header('Location: login_account');
exit();
?>
