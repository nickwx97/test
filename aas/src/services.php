<?php

include 'class/ListingManagement.php';
include 'class/IListing.php';

$listingManagementObj = new ListingManagement();

if (isset($_SESSION['last_activity'])){

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

    if($_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { 
        //redirect to logout.php
        header('Location: http://aas.sitict.net/logout.php'); 
    } 
    
    else{ //if we haven't expired:
        $_SESSION['last_activity'] = time(); 
    }
}

else{
    if (($_SESSION['role'] == 'guest')) {
        $_SESSION['logged_in'] = true; //set you've logged in
        $_SESSION['last_activity'] = time(); //your last activity was now, having logged in.
        $_SESSION['expire_time'] = 15*60; //expire time in seconds: 15minutes 

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
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Team AAS - Our Services</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/icon" href="asset/mader6.jpg" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <?php
    include "./navbar.php";
    ?>
    <section class="py-5">
        <div class="container my-5">
            <br />
            <h1 align="center">Our Services</h1>
            <form method="POST" class="form-inline" action="services.php">
                <label>Search Specialisation:</label>
                <input type="text" class="form-control" name="search" required="required"
                    pattern="^(?=.{1,40}$)[a-zA-Z]+(?:[-'\s][a-zA-Z]+)*$" />
                <button class="btn btn-success" type="submit" name="filter"><i class="fa fa-filter"
                        aria-hidden="true"></i>Filter</button>
                <a href="services.php" class="btn btn-warning"><i class="fa fa-refresh" aria-hidden="true"></i></a>
            </form>
            <br />
            <div class="row">
                <?php
            $ilistings = new IListing();
            if (isset($_POST['filter'])) {
                $listings = $listingManagementObj->searchListingRecord();
                if ($listings != null) {
                    foreach ($listings as $listing) {
            ?>
                <div class="col-sm-4">
                    <div class="card">
                        <img class="card-img-top" src="images/<?php echo $listing['profile_pic'] ?>" alt="profile pic"
                            style="height:300px; width:100%;">
                        <div class="card-body">
                            <h4 class="card-title">
                                <p align="center">
                                    <b><?php echo $listing['first_name'] ?>&nbsp;<?php echo $listing['last_name'] ?>
                                        <br /></b>
                                    <?php echo $listing['job_title'] ?>
                                </p>
                            </h4>
                            <p class="card-text">
                                Specialisation: <?php echo $listing['specialisation'] ?>
                                <br />
                                Years of Experience: <?php echo $listing['years_exp'] ?>
                            </p>
                            <button type="button" class="btn btn-info" data-toggle="modal"
                                data-target="#message<?php echo $listing['listing_id']; ?>">
                                See Listing
                            </button>
                        </div>

                        <div class="modal fade" id="message<?php echo $listing['listing_id']; ?>">
                            <div class=" modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">
                                            <img class="card-img-top" src="images/<?php echo $listing['profile_pic'] ?>"
                                                alt="profile pic" style="width:100%; padding-right:30px;">
                                        </h4>
                                        <p>
                                            <b><?php echo $listing['first_name'] ?>&nbsp;<?php echo $listing['last_name'] ?>
                                            </b>
                                            <br />
                                            <?php echo $listing['job_title'] ?>
                                            <br />
                                            Specialisation:<?php echo $listing['specialisation'] ?>
                                            <br />
                                            Years of Experience: <?php echo $listing['years_exp'] ?>
                                            <br />
                                            LinkedIn: <a target="_blank"
                                                href="<?php echo $listing['linkedin'] ?>"><?php echo $listing['linkedin'] ?></a>
                                        </p>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $listing['listing_content'] ?>
                                    </div>

                                    <?php
                                $r = "";
                                if (isset($_SESSION['username'])) {
                                    $u = $_SESSION['username'];
                                    $r = $_SESSION['role'];
                                }
                                ?>
                                    <?php
                                        if ($r == 'guest') { ?>
                                    <form action="guestAppt.php?employee_id=<?php echo $listing['employee_id'] ?>"
                                        method="post">
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning">
                                                Book Appointment</button>
                                        </div>
                                    </form>
                                    <?php
                                        } else {
                                        ?>
                                    <p align="center"> Please <a href="login.php">login</a> to book an appointment</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }
                } ?>

                <? } else {
                $listings = $ilistings->displayAllListingData();
                if ($listings != null) {
                    foreach ($listings as $listing) {
                ?>

                <div class="col-sm-4">
                    <div class="card">
                        <img class="card-img-top" src="images/<?php echo $listing['profile_pic'] ?>" alt="profile pic"
                            style="height:300px; width:100%;">
                        <div class="card-body">
                            <h4 class="card-title">
                                <p align="center">
                                    <b><?php echo $listing['first_name'] ?>&nbsp;<?php echo $listing['last_name'] ?>
                                        <br /></b>
                                    <?php echo $listing['job_title'] ?>
                                </p>
                            </h4>
                            <p class="card-text">
                                Specialisation:<?php echo $listing['specialisation'] ?>
                                <br />
                                Years of Experience: <?php echo $listing['years_exp'] ?>
                            </p>
                            <button type="button" class="btn btn-info" data-toggle="modal"
                                data-target="#message<?php echo $listing['listing_id']; ?>">
                                See Profile
                            </button>
                        </div>

                        <div class="modal fade" id="message<?php echo $listing['listing_id']; ?>">
                            <div class=" modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">
                                            <img class="card-img-top" src="<?php echo $listing['profile_pic'] ?>"
                                                alt="profile pic" style="width:100%; padding-right:30px;">
                                        </h4>
                                        <p>
                                            <b><?php echo $listing['first_name'] ?>&nbsp;<?php echo $listing['last_name'] ?>
                                            </b>
                                            <br />
                                            <?php echo $listing['job_title'] ?>
                                            <br />
                                            Specialisation:<?php echo $listing['specialisation'] ?>
                                            <br />
                                            Years of Experience: <?php echo $listing['years_exp'] ?>
                                            <br />
                                            LinkedIn: <a target="_blank"
                                                href="<?php echo $listing['linkedin'] ?>"><?php echo $listing['linkedin'] ?></a>
                                        </p>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $listing['listing_content'] ?>
                                    </div>

                                    <?php
                                        $r = "";
                                        if (isset($_SESSION['username'])) {
                                            $u = $_SESSION['username'];
                                            $r = $_SESSION['role'];
                                        }
                                        ?>
                                    <?php
                                        if ($r == 'guest') { ?>
                                    <form action="guestAppt.php?employee_id=<?php echo $listing['employee_id'] ?>"
                                        method="post">
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning">
                                                Book Appointment</button>
                                        </div>
                                    </form>
                                    <?php
                                        } else {
                                        ?>
                                    <p align="center"> Please <a href="login.php">login</a> to book an appointment</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }
                }
            } ?>
            </div>
        </div>
    </section>
</body>

<?php
include "./footer.php";
?>

</html>