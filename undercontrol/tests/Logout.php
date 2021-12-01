<?php

session_start();

function logout()
{
    if (isset($_SESSION)) {
        $_SESSION['is_logged_in'] = false;
        session_destroy();
    }
}
