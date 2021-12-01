<?php
session_start();

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

// csrf token for addListings form
if (empty($_SESSION['addListings-csrf'])){
    $_SESSION['addListings-csrf'] = bin2hex(random_bytes(32));
}

include 'class/ListingManagement.php';
include 'class/IListing.php';

//<!-- redirect user to login if role is not EMPLOYEE -->
if (!$_SESSION['role'] == 'employee') {
    header('location:login.php');
    exit;
}

if($_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { 
    //redirect to logout.php
    header('Location: http://aas.sitict.net/logout.php'); 
} 
else{ 
    //if we haven't expired:
    $_SESSION['last_activity'] = time(); 
}

$ilisting = new IListing();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Add Listings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>

<body>
    <?php
include "./navbar.php";
?>

    <br />
    <section class="py-5">
        <div class="container my-5">
            <h2>Add Listings</h2>
            <form method="POST" action="addListingsProcess.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="listing_content">Listing Content:</label>
                    <textarea class="form-control" id="listing_content" name="listing_content" required
                        pattern="^(?=.{40,700}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$"></textarea>
                </div>
                <div class="form-group">
                    <input hidden name="employee_id" value="<?php echo $_SESSION['employee_id']; ?>">
                </div>
                <div class="form-group">
                    <input hidden name="addListToken" value="<?php echo $_SESSION['addListings-csrf']; ?>">
                </div>
                <input type="submit" name="submit" class="btn btn-success" style="float:left;" value="Submit">
                <input type="button" name="cancel" class="btn btn-danger" style="float:left;" value="Cancel"
                    formnovalidate onclick="location.href='employee.php'">
            </form>
        </div>
    </section>
</body>
<?php
include "./footer.php";
?>

</html>