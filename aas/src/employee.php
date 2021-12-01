<?php
session_start();

include 'class/IListing.php';
include 'class/ListingManagement.php';

$listingManagementObj = new ListingManagement();
$ilistings = new IListing();

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

//<!-- redirect user to login if role is not EMPLOYEE -->
if (!$_SESSION['role'] == 'employee') {
    header('location:login.php');
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
    $listingManagementObj->deleteListingRecord($deleteId);
}

//fetch records
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user = $_GET['user_id'];
    $ilistings->displayListingData($user);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Employee</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/icon" href="asset/mader3.jpg" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

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
              Appointment created successfully
            </div>";
}
if (isset($_GET['msg2']) == "update") {
    echo "<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
             Appointment updated successfully
            </div>";
}
if (isset($_GET['msg3']) == "delete") {
    echo "<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              Appointment deleted successfully
            </div>";
}
if (isset($_GET['msg4']) == "insert") {
    echo "<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              Listings created successfully
            </div>";
}
if (isset($_GET['msg5']) == "update") {
    echo "<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
             Listings updated successfully
            </div>";
}
if (isset($_GET['msg6']) == "delete") {
    echo "<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
              Listings deleted successfully
            </div>";
}
if (isset($_GET['msg7']) == "insert") {
    echo "<div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert'>&times;</button>
             profile updated successfully
            </div>";
}

?>
        <h1 class="mt-5 text-center">Welcome Back, <?php echo $_SESSION['username']; ?>
            <br />Role: <?php echo $_SESSION['role']; ?>
            <br />User id: <?php echo $_SESSION['user_id']; ?>
            <br />Emp id: <?php echo $_SESSION['employee_id']; ?>

            <table class="table-responsive" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>User Id</th>
                        <th>Listing Id</th>
                        <th>Emp Id</th>
                        <th>Listing Content</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
$listings = $ilistings->displayListingData($_SESSION['employee_id']);
if ($listings != null) {
    foreach ($listings as $listing) {
        ?>
                    <tr>
                        <td><?php echo $listing['user_id'] ?></td>
                        <td><?php echo $listing['listing_id'] ?></td>
                        <td><?php echo $listing['employee_id'] ?></td>
                        <td><?php echo $listing['listing_content'] ?></td>
                        <td>
                            <a href="updateListings.php?editId=<?php echo $listing['listing_id'] ?>"
                                style="color:green;float:right;font-size:28px">
                                <i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp
                            <a href="employee.php?deleteId=<?php echo $listing['listing_id'] ?>" style="color:red"
                                onclick="confirm('Are you sure want to delete this listings?')">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                        </td>
                    </tr>
                    <?php }} else {?>
                    <?php echo 'No listings available';} ?>
                    <br />
                    <form action="addListings.php" method="post">
                        <input class="btn btn-danger" type="submit" name="submit" value="Add Listings">
                    </form>
                </tbody>
            </table>
    </div>
</body>

<?php
include "./footer.php";
?>

</html>