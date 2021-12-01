<?php
include_once "class/UserManagement.php";
$userManagementObj = new UserManagement();

$success = true;
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

    // validation for username
    if (empty($_POST['username'])) {
        $errorMsg .= "Username is required.<br>";
        $success = false;
    } else {
        #check if its letters or space
        if (preg_match("/[^a-zA-Z\s]+$/", $username)) {
            # means no letter or space
            $errorMsg .= "Invalid username.<br>";
            $success = false;
        } else {
            #means got letter
            if (strlen($username) >= 40) {
                $errorMsg .= "Username too long.<br>";
                $success = false;
            }
        }
    }

    // validation for first_name
    if (empty($_POST['first_name'])) {
        $errorMsg .= "First name is required.<br>";
        $success = false;
    } else {
        #check if its letters or space
        if (preg_match("/[^a-zA-Z\s]+$/", $first_name)) {
            # means no letter or space
            $errorMsg .= "Invalid first name.<br>";
            $success = false;
        } else {
            #means got letter
            if (strlen($first_name) >= 40) {
                $errorMsg .= "First name too long.<br>";
                $success = false;
            }
        }
    }

    // validation for last_name
    if (empty($_POST['last_name'])) {
        $errorMsg .= "Last name is required.<br>";
        $success = false;
    } else {
        #check if its letters or space
        if (preg_match("/[^a-zA-Z\s]+$/", $last_name)) {
            # means no letter or space
            $errorMsg .= "Invalid last name.<br>";
            $success = false;
        } else {
            #means got letter
            if (strlen($first_name) >= 40) {
                $errorMsg .= "Last name too long.<br>";
                $success = false;
            }
        }
    }

    // validation for contact_no
    if (empty($_POST['contact_no'])) {
        $errorMsg .= "Phone number  is required.<br>";
        $success = false;
    } else {
        if (!preg_match("/^([6,8,9][0-9]{7}+)$/", $_POST['contact_no'])) {
            $errorMsg .= "Invalid phone number.<br>";
            $success = false;
        }
    }

    // validation for email
    if (empty($_POST['email'])) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else {
        // Additional check to make sure e-mail address is well-formed
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.";
            $success = false;
        }
    }

    // validation for password
    if (empty($_POST['password'])) {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    } else {
        if (strlen($_POST['password']) < 8 || strlen($_POST['password']) > 30) {
            $errorMsg .= "Your password must be between 8 and 30 characters!<br>";
            $success = false;
        } elseif (!preg_match("#[0-9]+#", $_POST['password'])) {
            $errorMsg .= "Your password must contain at least 1 number!<br>";
            $success = false;
        } elseif (!preg_match("#[A-Z]+#", $_POST['password'])) {
            $errorMsg .= "Your password must contain at least 1 uppercase letter!<br>";
            $success = false;
        } elseif (!preg_match("#[a-z]+#", $_POST['password'])) {
            $errorMsg .= "Your password must contain at least 1 lowercase letter!<br>";
            $success = false;
        } elseif (preg_match("#[\W]+#", $_POST['password'])) {
            $errorMsg .= "Your password must not contain any special characters!<br>";
            $success = false;
        }
    }

    // validation for profile pic upload
    if (empty($_FILES["profile_pic"])) {
        $errorMsg .= "Profile picture is required.<br>";
        $success = false;
    } else {
        if ($_FILES["profile_pic"]["error"] !== UPLOAD_ERR_OK){
            $errorMsg .= "Upload failed. <br>";
            $success = false;
        }
        // check extension & mime type of uploaded file
        $mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES["profile_pic"]["tmp_name"]);
        $file_extension =  pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION);
        $allowed_extensions = array("png", "jpg", "jpeg");
        $allowed_mimes = array("image/png", "image/jpg", "image/jpeg");
        
        if ($_FILES["profile_pic"]["size"] === 0){
            $errorMsg .= "File uploaded is empty.<br>";
            $success = false;
        } elseif ($_FILES["profile_pic"]["size"] > 2097152){
            $errorMsg .= "Image size too large (Max: 2 MB). <br>";
            $success = false;
        } elseif (!in_array(strtolower($file_extension), $allowed_extensions)){
            $errorMsg .= "File extension not allowed. <br>";
            $success = false;
        } elseif (!in_array($mime_type, $allowed_mimes)) {
            $errorMsg .= "Content type not allowed. <br>";
            $success = false;
        }
    }
    

    if (isset($_POST["submit"]) && $success == true) {
        $newdata['username'] = $_POST['username'];
        $newdata['contact_no'] = $_POST['contact_no'];
        $newdata['first_name'] = $_POST['first_name'];
        $newdata['last_name'] = $_POST['last_name'];
        $newdata['email'] = $_POST['email'];
        $newdata['password'] = $_POST['password'];
        $newdata['dob'] = $_POST['dob'];

        $filename = $_FILES["profile_pic"]["name"];
        $tempname = $_FILES["profile_pic"]["tmp_name"];
        // $folder = "img/" . $filename;

        if (!$userManagementObj->isUsernameExists($newdata['username'])) {
            if (!$userManagementObj->isUserExist($newdata['email'])) {
                if ($userManagementObj->registration($newdata)) {
                    $success = "Registration successful please login";
                } else {
                    $errorMsg = "Registration failed please try again!";
                }
            } else {
                $errorMsg = "Email already exists! Please try again.";
            }
        } else {
            $errorMsg = "Username already exists! Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Registration Details</title>
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
    <section class="py-5">
        <div class="container my-5">
            <h2>Registration Details</h2>

            <?php
    if (!empty($errorMsg)) {
        echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button> $errorMsg
                  </div>";
        echo ("<button class='btn btn-danger' onclick=\"location.href='registration.php'\">Return to Registration Page</button>");
    } elseif (!empty($success)) {
        echo "<div class='alert alert-success alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>$success
                  </div>";
        echo ("<button class='btn btn-primary' onclick=\"location.href='login.php'\">Return to login page</button>");
    }
    ?>
        </div>
    </section>
    <?php
    include "./footer.php";