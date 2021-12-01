<?php
include 'class/UserManagement.php';

//redirect user to login if role is not ADMIN
if (!$_SESSION['role'] == 'admin') {
    header('location:login.php');
    exit;
}

if (!$_SESSION['user_agent'] == $_SERVER['HTTP_USER_AGENT']){
    header('location:logout.php');
    exit;
}

else{
    if (!$_SESSION['remote_ip'] == $_SERVER['REMOTE_ADDR']){
        header('location:logout.php');
        exit;
    }
}


// csrf token for addAccount form
if (empty($_SESSION['addAccount-csrf'])){
    $_SESSION['addAccount-csrf'] = bin2hex(random_bytes(32));
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
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <?php
include "./navbar.php";
?>
    <br />

    <?php
if (!empty($errorMsg)) {
    echo "<div class='alert alert-danger alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>  $errorMsg
                  </div>";
} elseif (!empty($success)) {
    echo "<div class='alert alert-success alert-dismissible'>
                     <button type='button' class='close' data-dismiss='alert'>&times;</button>$success
                  </div>";
}
?>

    <br />

    <section class="py-5">
        <div class="container my-5">
            <h2>Add Employee Account</h2>
            <form method="post" action="addAccountProcess.php" enctype="multipart/form-data">
                <div class=" form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required
                        pattern="^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$">
                </div>
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required
                        pattern="^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required
                        pattern="^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                </div>
                <div class="form-group">
                    <label for="contact_no">Contact No:</label>
                    <input type="text" class="form-control" id="contact_no" name="contact_no" required
                        pattern="[6,8,9][0-9]{7}">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" class="form-control" id="dob" name="dob">
                </div>
                <div class="form-group">
                    <label for="profile_pic">Profile Picture (Max size 3MB):</label>
                    <input type="file" class="form-control-file" name="profile_pic" required
                        accept=".png,.jpg,.jpeg">
                </div>
                <div class="form-group">
                    <label for="job_title">Job Title:</label>
                    <input type="text" class="form-control" id="job_title" name="job_title" required
                        pattern="^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$">
                </div>
                <div class="form-group">
                    <label for="years_exp">Years of Experience:</label>
                    <input type="number" class="form-control" id="years_exp" name="years_exp" min="1" max="100"
                        required>
                </div>
                <div class="form-group">
                    <label for="specialisation">Specialisation:</label>
                    <input type="text" class="form-control" id="specialisation" name="specialisation" required
                        pattern="^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$">
                </div>
                <div class="form-group">
                    <label for="linkedin">Linkedin:</label>
                    <input type="url" class="form-control" id="linkedin" name="linkedin" size="30"
                        placeholder="https://example.com" required>
                </div>

                <input type="hidden" class="form-control" id="role" name="role" value="employee">
                <input type="hidden" class="form-control" id="verified" name="verified" value="1">
                <input type="hidden" class="form-control" name="addAccToken"
                    value="<?php echo $_SESSION['addAccount-csrf']; ?>">

                <input type="submit" name="submit" class="btn btn-success" style="float:left;" value="Submit">
                <input type="button" name="cancel" class="btn btn-danger" style="float:left;" value="Cancel"
                    formnovalidate onclick="location.href='admin.php'">
            </form>
        </div>
    </section>
</body>
<?php
include "./footer.php";
?>

</html>