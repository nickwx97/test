<?php
session_start();
include 'class/UserManagement.php';

//<!-- redirect user to login if role is not ADMIN or employee or guest -->
if (!$_SESSION['role'] == 'admin' || !$_SESSION['role'] == 'employee' || !$_SESSION['role'] == 'guest') {
    header('location:login.php');
    exit;
}

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

    $first_name = test_input($_POST["first_name"]);
    $last_name = test_input($_POST["last_name"]);
    $contact_no = test_input($_POST["contact_no"]);
    $email = test_input($_POST["email"]);
    
    // validation for first name
    if (empty($first_name)) {
        $errorMsg .= "First name is required.<br>";
        $success = false;
    } else {
        if (preg_match("/^([a-zA-Z' ]+)$/", $_POST['first_name'])) {
            if (strlen($first_name) >= 40) {
                $errorMsg .= "First name too long.<br>";
                $success = false;
            }
        } else {
            $errorMsg .= "Invalid first name.<br>";
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
            $errorMsg .= "Invalid Last name.<br>";
            $success = false;
        }
    }

    // validation for contact number
    if (empty($contact_no)) {
        $errorMsg .= "Phone number  is required.<br>";
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

    // validation for profile pic upload
    if (!empty($_FILES["profile_pic"]["name"])){
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


    $user_id = $_POST['user_id'];
    // checks csrf token in form
    if(!empty($_POST['editProfileToken'])){
        if(hash_equals($_SESSION['editProfile-csrf'], $_POST['editProfileToken'])){
            if (isset($_POST["submit"]) && $success == true) {
                $user_id = $_POST['user_id'];
                $newdata['contact_no'] = $_POST['contact_no'];
                $newdata['first_name'] = $_POST['first_name'];
                $newdata['last_name'] = $_POST['last_name'];
                $newdata['email'] = $_POST['email'];
                $newdata['dob'] = $_POST['dob'];

                $file_type = $_FILES['profile_pic']['type'];
                $filename = $_FILES["profile_pic"]["name"];
                $tempname = $_FILES["profile_pic"]["tmp_name"];
                $folder = "images/" . $filename;

                if ($userManagementObj->updateProfileRecord($newdata)) {
                    $success = "Profile Updated Successfully";
                    $_SESSION['editProfile-csrf'] = bin2hex(random_bytes(32)); // regenerate csrf token after form submission
                } else {
                    $errorMsg = "Update failed please try again!";
                    $_SESSION['editProfile-csrf'] = bin2hex(random_bytes(32));
                }
            } else {
                $errorMsg = "Update failed please try again!";
                $_SESSION['editProfile-csrf'] = bin2hex(random_bytes(32));
            }
        } else {
            $errorMsg = "Update failed please try again!";
            $_SESSION['editProfile-csrf'] = bin2hex(random_bytes(32));
        }
    } else {
        $errorMsg = "Update failed please try again!";
        $_SESSION['editProfile-csrf'] = bin2hex(random_bytes(32));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Update Profile Details</title>
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

    <h2>Update Profile Details</h2>
    <section class="py-5">
        <div class="container my-5">
            <?php
if (!empty($errorMsg)) {
    echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>  $errorMsg
                  </div>";
    echo ("<button class='btn btn-danger' onclick=\"location.href='editProfile.php?editId=$user_id'\">Return to Update records</button>");

} elseif (!empty($success)) {
    echo "<div class='alert alert-success alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>$success
                  </div>";
    echo ("<button class='btn btn-primary' onclick=\"location.href='index.php?editId=$user_id'\">Return to Index page</button>");
}

?>
        </div>
    </section>
    <?php
include "./footer.php";