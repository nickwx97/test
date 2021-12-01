<?php
session_start(); 
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'retrieveUserPrivilege') {
        if(isset($_SESSION)) {
            $user_privilege = $_SESSION['user_privilege'];
            $is_logged_in = $_SESSION['is_logged_in'];

            if ($user_privilege != 0) {
                $success = true;
            } else {
                $success = false;
            }
        }

        $json_array = array(
            'success' => $success,
            'user_privilege' => $user_privilege,
            'is_logged_in' => $is_logged_in
        );
        echo json_encode($json_array);
    }
}
?>
