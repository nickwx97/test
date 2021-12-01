<?php
// Always do a check if no existing session, create it!
// This will check for recreation of a new session as well to generate a new session id
if (session_id() == '' and session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 0;
}

if (!isset($_SESSION['fullname'])) {
    $_SESSION['fullname'] = "";
}

if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = "";
}

if (!isset($_SESSION['email'])) {
    $_SESSION['email'] = "";
}

if (!isset($_SESSION['user_privilege'])) {
    $_SESSION['user_privilege'] = 0;
}

if (!isset($_SESSION['is_logged_in'])) {
    $_SESSION['is_logged_in'] = false;
}
if (!isset($_SESSION['regenOTPcount'])) {
    $_SESSION['regenOTPcount'] = 0;
}

$minimal_user_privilege = 1;

if ($_SESSION['is_logged_in'] and isset($_SESSION['LAST_ACTIVITY_UPDATE'])) {
    // Only if user is logged in, then perform this check
    // Session timeout if user is inactive for more than 60 mins
    if ((time() - $_SESSION['LAST_ACTIVITY_UPDATE']) > 3600) {
        header("location:logout.php");
        exit();
    } else {
        // Update the last activity time
        $_SESSION['LAST_ACTIVITY_UPDATE'] = time();
    }
}
?>

<header id="navigations" class="navbar-fixed-top navbar" style="background-color: #6a737b;">
    <div class="container">
        <div class="navbar-header">
            <!-- responsive nav button -->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <i class="fa fa-bars fa-2x"></i>
            </button>
            <!-- /responsive nav button -->

            <!-- logo -->
            <a class="navbar-brand" href="index">
                <h1 id="logo">
                    <img src="img/logowfire.gif" alt="FireAway">
                </h1>
            </a>
        </div>

        <!-- main nav -->
        <nav class="collapse navbar-collapse navbar-right" role="navigation">
            <ul id="nav" class="nav navbar-nav">
                <li class="current"><a href="index#home">Home</a></li>
                <li><a href="index#features">About Us</a></li>
                <li><a href="index#category">Introduction</a></li>
                <li><a href="index#team">Team</a></li>
                <li><a href="index#facts">Facts</a></li>
                <li><a href="index#contact">Contact Us</a></li>

                <div class="btn-group" style="margin-top: 9px;">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php
                    if(isset($_SESSION)) {
                        if(($_SESSION['username'] !== "") && ($_SESSION['is_logged_in'] == true)) {
                           echo $_SESSION['username'];
                        } else {
                            echo 'Employee';
                        }
                    }?>
                    </button>
                    <div class="dropdown-menu ">
                      <?php
                     if(isset($_SESSION)) {
                        if(($_SESSION['username'] !== "") && ($_SESSION['is_logged_in'] == true)) {
                          echo '<li> <a class="dropdown-item" onclick="return dashboard();"href="">Dashboard</a></li>
                            <li> <a class="dropdown-item" onclick="return false;" href="" id="btnUpdateMyAccount">Update My Account</a></li>
                            <li> <a class="dropdown-item" onclick="return logout();" href="">Logout</a></li>';
                        }
                        else {
                            echo '<li> <a class="dropdown-item" onclick="return login();" href="" id="login">Login</a></li>';
                        }
                        }
                        ?>
                    </div>
                </div>
            </ul>
        </nav>
        <!-- /main nav -->
    </div>


    <!--
    Update My Account Popup
    ==================================== -->
    <div class="modal fade" id="updateMyAccountModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content -->
            <div id="modal-box" class="col-md-12">
                <form id="update-my-account-form" class="form" name="updateMyAccountForm" action="" method="post">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="text-center text-danger">Update My Account</h3>
                        <div class="form-group">
                            <label for="updateFullName" class="text-danger">Full Name:</label><br>
                            <input type="text" id="updateFullName" name="updateFullName" value="<?php echo $_SESSION['fullname'];?>" placeholder="Please enter your full name" class="form-control">
                            <span class="error" id="errorUpdName"></span>
                        </div>
                        <div class="form-group">
                            <label for="updateUsername" class="text-danger">Username:</label><br>
                            <input type="text" id="updateUsername" name="updateUsername" value="<?php echo $_SESSION['username'];?>" placeholder="Please enter your username" class="form-control">
                            <span class="error" id="errorUpdUsername"></span>
                        </div>
                        <div class="form-group">
                            <label for="updateEmail" class="text-danger">Email:</label><br>
                            <input type="email" id="updateEmail" name="updateEmail" value="<?php echo $_SESSION['email'];?>" placeholder="Please enter your email address" class="form-control">
                            <span class="error" id="errorUpdEmail"></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submitOwnInfo" class="btn btn-success btn-md" value="Submit" onclick="validateUpdateOwnInfo('<?php echo $_SESSION['user_id']?>');">
                        </div>
                        <div id="popup-button" class="text-right">
                            <button id="btnCancel" type="button" class="btn btn-danger btn-md" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
