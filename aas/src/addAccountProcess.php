<?php
include 'class/UserManagement.php';

//redirect user to login if role is not ADMIN
if (!$_SESSION['role'] == 'admin') {
    header('location:login.php');
    exit;
}
?>

<?php

$userManagementObj = new UserManagement();

$success = true;
$error = "";
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    $username = test_input($_POST["username"]);
    $first_name = test_input($_POST["first_name"]);
    $last_name = test_input($_POST["last_name"]);
    $contact_no = test_input($_POST["contact_no"]);
    $email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);
    $dob = test_input($_POST["dob"]);
    $job_title = test_input($_POST["job_title"]);
    $specialisation = test_input($_POST["specialisation"]);
    $years_exp = test_input($_POST["years_exp"]);
    $linkedin = test_input($_POST["linkedin"]);

    // validation for username
    if (empty($username)) {
        $errorMsg = "Username is required";
        $success = false;
    } else {
        if (preg_match("/^([a-zA-Z' ]+)$/", $username)) {
            if (strlen($_POST['username']) >= 40) {
                $errorMsg .= "Username too long.<br>";
                $success = false;
            }
        } else {
            $errorMsg .= "Invalid username.<br>";
            $success = false;
        }
    }
    // validation for first name
    if (empty($first_name)) {
        $errorMsg .= "First name is required.<br>";
        $success = false;
    } else {
        if (preg_match("/^([a-zA-Z' ]+)$/", $first_name)) {
            if (strlen($first_name) >= 40) {
                $errorMsg .= "First name too long.<br>";
                $success = false;
            }
        } else {
            $errorMsg .= "Invalid First name.<br>";
            $success = false;
        }
    }

    // validation for last name
    if (empty($last_name)) {
        $errorMsg .= "Last name is required.<br>";
        $success = false;
    } else {
        if (preg_match("/^([a-zA-Z' ]+)$/", $last_name)) {
            if (strlen($last_name) >= 40) {
                $errorMsg .= "Last name too long.<br>";
                $success = false;
            }
        } else {
            $errorMsg .= "Invalid last name.<br>";
            $success = false;
        }
    }
    
    // validation for contact number
    if (empty($contact_no)) {
        $errorMsg .= "Phone number is required.<br>";
        $success = false;
    } else {
        if (!preg_match("/^([6,8,9][0-9]{7}+)$/", $contact_no)) {
            $errorMsg .= "Invalid phone number.<br>";
            $success = false;
        }
    }
    
    // validation for email
    if (empty($email)) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else {
        // Additional check to make sure e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.";
            $success = false;
        }
    }
    
    // validation for password
    if (empty($password)) {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    } else {
        if (strlen($password) < 8 || strlen($password) > 30) {
            $errorMsg .= "Your password must be between 8 and 30 characters!<br>";
            $success = false;
        } elseif (!preg_match("#[0-9]+#", $password)) {
            $errorMsg .= "Your password must contain at least 1 number!<br>";
            $success = false;
        } elseif (!preg_match("#[A-Z]+#", $password)) {
            $errorMsg .= "Your password must contain at least 1 uppercase letter!<br>";
            $success = false;
        } elseif (!preg_match("#[a-z]+#", $password)) {
            $errorMsg .= "Your password must contain at least 1 lowercase letter!<br>";
            $success = false;
        } elseif (preg_match("#[\W]+#", $password)) {
            $errorMsg .= "Your password must not contain any special characters!<br>";
            $success = false;
        }
    }


    // validation for job_title
    if (empty($job_title)) {
        $errorMsg .= "Job title is required.<br>";
        $success = false;
    } else {
        #check if its letters or space
        if (preg_match("/[^a-zA-Z\s]+$/", $job_title)) {
            # means no letter or space
            $errorMsg .= "Invalid job title.<br>";
            $success = false;
        } else {
            #means got letter
            if (strlen($job_title) >= 40) {
                $errorMsg .= "Job title too long.<br>";
                $success = false;
            }
        }
    }

    // validation for specialisation
    if (empty($specialisation)) {
        $errorMsg .= "Specialisation is required.<br>";
        $success = false;
    } else {
        #check if its letters or space
        if (preg_match("/[^a-zA-Z\s]+$/", $specialisation)) {
            # means no letter or space
            $errorMsg .= "Invalid specialisation.<br>";
            $success = false;
        } else {
            #means got letter
            if (strlen($specialisation) >= 40) {
                $errorMsg .= "Specialisation too long.<br>";
                $success = false;
            }
        }
    }


    // validation for years_exp
    if (empty($years_exp)) {
        $errorMsg .= "Years of experience is required.<br>";
        $success = false;
    } else {
        if (!filter_var($years_exp, FILTER_VALIDATE_INT)) {
            $errorMsg .= "Years of experience must be in numbers only.<br>";
            $success = false;
        }
    }

    // validation for linkedin
    if (empty($linkedin)) {
        $errorMsg .= "LinkedIn profile is required.<br>";
        $success = false;
    } else {
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $linkedin)) {
            $errorMsg .= "Invalid URL";
            $success = false;
        }
    } 

    if(!empty($_POST['addAccToken'])){
        if(hash_equals($_SESSION['addAccount-csrf'], $_POST['addAccToken'])){
           if (isset($_POST["submit"]) && $success == true) {
                $newdata['username'] = $_POST['username'];
                $newdata['contact_no'] = $_POST['contact_no'];
                $newdata['first_name'] = $_POST['first_name'];
                $newdata['last_name'] = $_POST['last_name'];
                $newdata['email'] = $_POST['email'];
                $newdata['password'] = $_POST['password'];
                $newdata['dob'] = $_POST['dob'];
                $newdata['job_title'] = $_POST['job_title'];
                $newdata['specialisation'] = $_POST['specialisation'];
                $newdata['years_exp'] = $_POST['years_exp'];
                $newdata['linkedin'] = $_POST['linkedin'];

                $file_type = $_FILES['profile_pic']['type'];
                $filename = $_FILES["profile_pic"]["name"];
                $tempname = $_FILES["profile_pic"]["tmp_name"];
                $folder = "images/" . $filename;

                if (!$userManagementObj->isUsernameExists($newdata['username'])) {
                    if (!$userManagementObj->isUserExist($newdata['email'])) {
                        if ($userManagementObj->insertAccount($newdata)) {
                            $success = "Registration Successful! Please login";
                            $_SESSION['addAccount-csrf'] = bin2hex(random_bytes(32)); //regenerate csrf token after form submission
                        } else {
                            $errorMsg = "Registration failed please try again!";
                            $_SESSION['addAccount-csrf'] = bin2hex(random_bytes(32));
                        }
                    } else {
                        $errorMsg = "Email already exists! Please try again.";
                        $_SESSION['addAccount-csrf'] = bin2hex(random_bytes(32));
                    }
                } else {
                    $errorMsg = "username already exists! Please try again.";
                    $_SESSION['addAccount-csrf'] = bin2hex(random_bytes(32));
                }
            }
        } else {
            $errorMsg = "Registration failed please try again!";
            $_SESSION['addAccount-csrf'] = bin2hex(random_bytes(32));
        }
    } else {
        $errorMsg = "Registration failed please try again!";
        $_SESSION['addAccount-csrf'] = bin2hex(random_bytes(32));
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Add Employee Account</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php
include "./navbar.php";
?>

    <br />
    <h2>Add Employee Account Details</h2>

    <section class="py-5">
        <div class="container my-5">
            <?php
if (!empty($errorMsg)) {
    echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>  $errorMsg
                  </div>";
    echo ("<button class='btn btn-primary' onclick=\"location.href='addAccount.php'\">Return to add account page</button>");

} elseif (!empty($success)) {
    echo "<div class='alert alert-success alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>$success
                  </div>";
    echo ("<button class='btn btn-primary' onclick=\"location.href='admin.php'\">Return to admin page</button>");

}
?>
        </div>
    </section>
    <?php
include "./footer.php";