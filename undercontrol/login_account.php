<!DOCTYPE html>
<?php
session_start();
if (isset($_SESSION['is_logged_in'])) {
    // Check if users are already logged in, block users from going to login page again
    if ($_SESSION['is_logged_in'] == true) {
        header('Location: dashboard');
    }
}
// Strict mechanism to force reset back the email to prevent redirecting back to login_otp.php with previous stored email in session
if (isset($_SESSION['email'])) {
    if ($_SESSION['email'] != "") {
        $_SESSION['email'] = "";
        $_SESSION['regenOTPcount'] = 0;
    }
}
?>
<html lang="en" class="no-js"> <!--<![endif]-->
    <head>
    	<!-- meta charec set -->
        <meta charset="utf-8">
		<!-- Always force latest IE rendering engine or request Chrome Frame -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<!-- Page Title -->
        <title>FireAway Account Login</title>
		<!-- Meta Description -->
        <meta name="description" content="Blue One Page Creative HTML5 Template">
        <meta name="keywords" content="one page, single page, onepage, responsive, parallax, creative, business, html5, css3, css3 animation">
        <meta name="author" content="Muhammad Morshed">
		<!-- Mobile Specific Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Google Font -->

		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>

		<!-- CSS
		================================================== -->
		<!-- Fontawesome Icon font -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
		<!-- Twitter Bootstrap css -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- jquery.fancybox  -->
        <link rel="stylesheet" href="css/jquery.fancybox.css">
		<!-- animate -->
        <link rel="stylesheet" href="css/animate.css">
		<!-- Main Stylesheet -->
        <link rel="stylesheet" href="css/main.css">
		<!-- media-queries -->
        <link rel="stylesheet" href="css/media-queries.css">

		<!-- Modernizer Script for old Browsers -->
        <script src="js/modernizr-2.6.2.min.js"></script>

	    <!-- Google API for calling reCAPTCHA -->
	    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>

    <body id="body">

        <div id="preloader">
			<img src="img/preloader.gif" alt="Preloader">
		</div>

        <!--
        Fixed Navigation
        ==================================== -->
        <?php include('header.php'); ?>

        <!--
        Login form -->
        <section id="login">
            <div class="container">
                <div id="login-row" class="row justify-content-center">
                    <div id="login-column" class="col-md-6">
                        <div id="login-box" class="col-md-12">
                            <form id="login-form" class="form" action="" method="post" onsubmit="return validateLogin()">
                                <h3 class="text-center text-danger">Login</h3>
                                <div class="form-group">
                                    <label for="username" class="text-danger">Email:</label><br>
                                    <input type="email" id="loginEmail" name="loginEmail" placeholder="Enter email" class="form-control">
                                    <span class="error" id="errorLogEmail"></span>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="text-danger">Password:</label><br>
                                    <input type="password" id="loginPassword" name="loginPassword" placeholder="Enter password" class="form-control">
                                    <span class="error" id="errorLogPass"></span>
                                </div>
				                <div class="form-group">
                                    <div class="g-recaptcha" data-sitekey="6Le_25YcAAAAAEugH78zsC-jNriflHhpAR3Rc_RA" data-callback="enableLoginBtn"></div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="submit" id="loginBtn" class="btn btn-success btn-md" value="Submit" disabled="disabled">
                                </div>
                                <div id="register-link" class="text-right">
                                    <a href="forget_pw_email" id="forgetPw" class="text-danger">Forget Password?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php include('footer.php'); ?>

        <!-- Essential jQuery Plugins
		================================================== -->
		<!-- Main jQuery -->
        <<script src="js/jquery-1.11.1.min.js"></script>

        <!-- Contact form validation -->
        <script type="text/javascript" src="js/bootstrap.min.js"></script>

        <!-- SRP Function-->
        <script type="text/javascript" src="js/bigint.js"></script>
        <script type="text/javascript" src="js/sha256.js"></script>
        <script type="text/javascript" src="js/srp.js"></script>

        <!-- Login Functions -->
        <script type="text/javascript" src="js/login.js"></script>

        <!-- Custom Functions -->
        <script src="js/custom.js"></script>

        <!-- validate Functions -->
        <script src="js/validationInput.js"></script>

        <!-- Captcha Functions -->
        <script type="text/javascript" src="js/captcha.js"></script>

        <script type="text/javascript" src="js/header.js"></script>
    </body>
</html>
