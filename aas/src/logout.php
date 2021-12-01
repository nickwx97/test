<?php
session_start();
function destroySession() {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
    session_destroy();
}
destroySession();
header("Location:index.php");
$log = "Successful Logout";
logger($log);