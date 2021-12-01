<?php

include 'class/UserManagement.php';
include 'class/IUser.php';

$userManagementObj = new UserManagement();

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

if (!$_SESSION['role'] == 'admin') {
    header('location:index.php');
    exit;
}

if (!isset($$_SESSION['last_activity']))
{
    $_SESSION['logged_in'] = true; //set you've logged in
    $_SESSION['last_activity'] = time(); //your last activity was now, having logged in.
    $_SESSION['expire_time'] = 15*60; //expire time in seconds: 15minutes 

}

else
{
    if($_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { 
        //redirect to logout.php
        header('Location: http://aas.sitict.net/logout.php'); 
    } else{ //if we haven't expired:
        $_SESSION['last_activity'] = time(); 
    }
}

// Delete record from table
if (isset($_GET['deleteId']) && !empty($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    $userManagementObj->deleteAccountRecord($deleteId);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/icon" href="asset/mader2.jpg" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>

<body>
    <?php
include "./navbar.php";
?>
    <br />

    <div class="container">
        <?php
if (isset($_GET['msg1']) == "insert") {
    echo "<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              Account Registration added successfully
            </div>";
}
if (isset($_GET['msg3']) == "delete") {
    echo "<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              Account deleted successfully
            </div>";
}
?>
        <p align="center"> <a href="addAccount.php" class="btn btn-danger">Add Account</a></p>

        <h1 class="mt-5 text-center">Welcome back, <?php echo $_SESSION['username']; ?>
            <br />Role: <?php echo $_SESSION['role']; ?>
            <table class="table-responsive table" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>user_id</th>
                        <th>Role</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Contact No</th>
                        <th>Email</th>
                        <th>DOB</th>
                        <th>Profile Pic</th>
                        <th>Created_at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
$iuser = new IUser();
$users = $iuser->displayPersonnelData();
if ($users != null) {
    foreach ($users as $user) {
        ?>
                    <tr>
                        <td><?php echo $user['user_id'] ?></td>
                        <td><?php echo $user['role'] ?></td>
                        <td><?php echo $user['username'] ?></td>
                        <td><?php echo $user['first_name'] ?></td>
                        <td><?php echo $user['last_name'] ?></td>
                        <td><?php echo $user['contact_no'] ?></td>
                        <td><?php echo $user['email'] ?></td>
                        <td><?php echo $user['dob'] ?></td>
                        <td><img style="max-height: 150px;" src="images/<?php echo $user['profile_pic'] ?>"></td>
                        <td><?php echo $user['created_at'] ?></td>
                        <td>
                            <a href="admin.php?deleteId=<?php echo $user['user_id'] ?>" style="color:red"
                                onclick="confirm('Are you sure want to delete this account')">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                        </td>
                    </tr>
                    <?php }} else {?>
                    <?php echo 'No records available';} ?>
                </tbody>
            </table>
    </div>
</body>
<?php
include "./footer.php";
?>

</html>