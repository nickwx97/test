<?php 
session_start(); 
if (isset($$_SESSION['last_activity'])){

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

    if( $_SESSION['last_activity'] < time()-$_SESSION['expire_time'] ) { 
        //redirect to logout.php
        header('Location: http://aas.sitict.net/logout.php'); 
    } else{ //if we haven't expired:
        $_SESSION['last_activity'] = time(); 
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>AAS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/icon" href="asset/solution.png" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <style>
    /* Make the image fully responsive */
    .carousel-inner img {
        width: 100%;
        height: 50%;
    }
    </style>
</head>

<body>
    <?php
    include "./navbar.php";
    ?>
    <!-- Header - set the background image for the header in the line below-->
    <header class="py-5 bg-image-full"
        style="background-image: url('https://source.unsplash.com/wfh8dDlNFOk/1600x900')">
        <div class="text-center my-5">
            <img class="img-fluid rounded-circle mb-4" src="https://i.imgur.com/Lg4Vk5y.jpg" style="max-width: 150px;"
                alt="..." />
            <!--https://images.pexels.com/photos/3861976/pexels-photo-3861976.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260
https://dummyimage.com/150x150/6c757d/dee2e6.jpg
                 <div>Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
                 https://i.imgur.com/Lg4Vk5y.jpg-->
            <!--<a href="https://www.freepik.com/vectors/business">Business vector created by vectorjuice - www.freepik.com</a> -->
            <h1 class="text-white fs-3 fw-bolder">Your one stop solution</h1>
            <p class="text-white-50 mb-0">Advanced Advisory Solutions</p>
        </div>
    </header>
    <!-- Content section-->
    <section class="py-5">
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <h2>Who are we</h2>
                    <p class="lead">We are an IT Solution and Service Provider based in Singapore</p>
                    <p class="mb-0">Our solutions leverage advanced technology, proprietary data, and deep expertise to
                        help clients in solving their business needs. By working with us, we'll make sure that your
                        business performs better than ever, as your improvement defines our success.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Image element - set the background image for the header in the line below-->
    <div class="py-5 bg-image-full" style="background-image: url('https://source.unsplash.com/4ulffa6qoKA/1200x800')">
        <!-- Put anything you want here! The spacer below with inline CSS is just for demo purposes!-->
        <div style="height: 20rem"></div>
    </div>
    <!-- Content section-->
    <section class="py-5">
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <h2>Contact Us</h2>
                    <p class="lead">Feel free to contact us to get an insight of how your business' technological
                        aspects can progess with our assistance</p>
                    <p class="mb-0">Alternatively, you can drop by our office located at 666 Ah Ah Sala Road #01-05
                        Singapore 999666 during office hours.
                </div>
            </div>
    </section>
    <!-- Footer-->
    <?php
include "./footer.php";
?>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>

</body>


</html>