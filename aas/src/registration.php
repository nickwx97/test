<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Registration</title>
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
            <h2>Registration</h2>
            <form method="post" action="registrationProcess.php" enctype="multipart/form-data">
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
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <div class="form-group">
                    <label for="profile_pic">Profile Picture (Max size 2MB):</label>
                    <input type="file" class="form-control-file" name="profile_pic" required
                        accept=".png,.jpg,.jpeg">
                </div>
                <input type="submit" name="submit" class="btn btn-primary" style="float:left;" value=" Sign Up">
                <input type="button" name="cancel" class="btn btn-danger" style="float:left;" value="Cancel"
                    formnovalidate onclick="location.href='login.php'">
            </form>
        </div>
    </section>
</body>
<?php
include "./footer.php";
?>

</html>