<?php

include 'class/ListingManagement.php';
include 'class/IListing.php';


$listingManagementObj = new ListingManagement();
$ilisting = new IListing();

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

// redirect user if they are not employee
if ((!$_SESSION['role'] == 'employee')) {
    header('location:index.php');
    exit;
}

//generate csrf token for change password form
if (empty($_SESSION['updateListing-csrf'])){
    $_SESSION['updateListing-csrf'] = bin2hex(random_bytes(32));
}

if($_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { 
    //redirect to logout.php
    header('Location: http://aas.sitict.net/logout.php'); 
} 

else{ //if we haven't expired:
    $_SESSION['last_activity'] = time(); 
}

// Edit customer record
if (isset($_GET['editId']) && !empty($_GET['editId'])) {
    $editId = $_GET['editId'];
    $listing = $ilisting->displayListingById($editId);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Update My Listings</title>
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
            <h2>Update My Listings</h2>
            <form action="updateListingsProcess.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="listing_content">Listing Content:</label>
                    <input type="text" class="form-control" id="listing_content" name="listing_content" required
                        value="<?php echo $listing['listing_content']; ?>" pattern="^(?=.{40,700}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$">
                </div>
                <div class="form-group">
                    <input type="hidden" id="employee_id" name="employee_id"    
                        value="<?php echo $listing['employee_id']; ?>">
                    <input type="hidden" name="listing_id" value="<?php echo $listing['listing_id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $listing['user_id']; ?>">
                    <input type="hidden" name="updateListingToken" value="<?php echo $_SESSION['updateListing-csrf']; ?>">
                </div>
                <input type="submit" name="submit" class="btn btn-success" style="float:left;" value="Submit">
                <input type="button" name="cancel" class="btn btn-danger" style="float:left;" value="Cancel"
                    formnovalidate onclick="location.href='employee.php?user_id=<?php echo $_SESSION['user_id'] ?>'">
            </form>
        </div>
    </section>
</body>

<?php
include "./footer.php";
?>

</html>