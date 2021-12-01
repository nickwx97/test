<?php

session_start();

use PHPUnit\Framework\TestCase;

include_once('Logout.php');
include_once('./validation_input.php');

// Testing in accordance to WSTG-SESS-06 
class SessionTest extends TestCase
{
    /**
     * @test
     * @runInSeparateProcess
     */
    public function testLogout() {
        // Test session logout status to simulate if an account has been logged out successfully
        $_SESSION['is_logged_in'] = true;
        $this->assertEquals(true, $_SESSION['is_logged_in']);

        logout();
        $this->assertEquals(false, $_SESSION['is_logged_in']);
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function testSessionTimeout() {
        /* Test that the session timeout process is working by resetting all the session variables
        back to default if the time of last activity is more than 60 minutes detected by the server*/
        $old_session_id = session_id();
        $new_session_id = $old_session_id;
        $this->assertEquals(true, $new_session_id == $old_session_id);

        // Simulate auto-logout process taking place
        $_SESSION['LAST_ACTIVITY_UPDATE'] = time() - 3601;

        if ((time() - $_SESSION['LAST_ACTIVITY_UPDATE']) > 3600) {
            // Destroy old session
            logout();

            // Issue new session id
            $new_session_id = session_id();
        }
        $this->assertEquals(false, $new_session_id == $old_session_id);
    }
}
?>