<?php
session_start();
if (isset($_SESSION['is_logged_in'])) {
    // If logout, block user from entering this page and redirect out
    if ($_SESSION['is_logged_in'] == false) {
        header('Location: login_account');
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <link rel="icon" href="img/fire_icon.png">

    <!-- meta charec set -->
    <meta charset="utf-8">
    <!-- Always force latest IE rendering engine or request Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- Page Title -->
    <title>FireAway Dashboard</title>
    <!-- Meta Description -->
    <meta name="description" content="Blue One Page Creative HTML5 Template">
    <meta name="keywords" content="one page, single page, onepage, responsive, parallax, creative, business, html5, css3, css3 animation">
    <meta name="author" content="Muhammad Morshed">
    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font -->

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- CSS
		================================================== -->
    <!-- Fontawesome Icon font -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Twitter Bootstrap css -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- jquery.fancybox  -->
    <link rel="stylesheet" href="css/jquery.fancybox.css">
    <!-- animate -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/main.css">
    <!-- media-queries -->
    <link rel="stylesheet" href="css/media-queries.css">

    <!-- Modernizer Script for old Browsers -->
    <script src="js/modernizr-2.6.2.min.js"></script>
    <!-- feedback js -->
    <script src="js/feedback.js"></script>

</head>

<body id="body" onload="validateUserPrivilege();">

    <div id="preloader">
        <img src="img/preloader.gif" alt="Preloader">
    </div>

    <!--
        Fixed Navigation
        ==================================== -->
    <?php include('header.php'); ?>

    <section id="features" class="features">
        <div class="container">
            <div class="row">
                <div class="sec-title text-center mb50 wow bounceInDown animated" data-wow-duration="500ms">
                    <h2>Dashboard</h2>
                    <div class="devider"><i class="fa fa-fire fa-lg"></i></div>
                </div>
            </div>

            <div class="container-fluid">
                <!-- Page Heading -->
                <!-- table tab -->
                <button id="information">Information</button>
                <button id="feedback">Feedback</button>
                <button id="btnAddEmployee" class="btn-success" ><i class="fa fa-plus"></i> Add</button>
                <div class="table-responsive" id="table1">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Employee Username</th>
                                <th>Employee Email</th>
                                <th>User Privilege</th>
                                <th>Employee Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require('db_mapper_factory.php');
                            require('account.php');
                            $dbMapperFactory = new DBMapperFactory();
                            $account_mapper_instance = $dbMapperFactory->createMapperInstance("Account");
                            $conn = $account_mapper_instance->readFile("config.txt");
                            $result = $account_mapper_instance->readAllFireAwayStaffFromDB($conn, "Account", $_SESSION['user_id']);
                            echo '<tr>';
                            if ($result->num_rows > 0) {
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo '<td>' . $row["account_fullname"] . '</td>';
                                    echo '<td>' . $row["account_username"] . '</td>';
                                    echo '<td>' . $row['account_email'] . '</td>';
                                    echo '<td>' . $row['user_privilege'] . '</td>';
                                    if ($row['user_privilege'] == "1") {
                                        echo '<td>' . "Employee" . '</td>';
                                    } else if ($row['user_privilege'] == "2") {
                                        echo '<td>' . "Manager" . '</td>';
                                    } else {
                                        echo '<td>' . "Administrator" . '</td>';
                                    }

                                    // Update Account Popup Modal
                                    echo ' <td><button id="btnUpdateEmployee"' . $row['account_id'] . ' class="btn-info" onclick="updateAccount(' . $row['account_id'] . ')" data-target="updateEmployeeModal' . $row['account_id'] . '"><i class="fa fa-pencil"></i> Update</button>';
                                    echo ' <button id="btnDeleteEmployee"' . $row['account_id'] . ' class="btn-danger" onclick="delAccount(' . $row['account_id'] . ')" data-target="deleteEmployeeConfirmationModal' . $row['account_id'] . '"><i class="fa fa-trash"></i> Delete</button></td>';
                                    echo '</tr>';

                                    echo '<div class="modal fade updateEmployeeModal" id="updateEmployeeModal'.$row['account_id'].'" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content -->
                                            <div id="modal-box" class="col-md-12">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h3 class="text-center text-danger">Update Employee Account</h3>
                                                <div class="form-group">
                                                    <label for="updateFullName" class="text-danger">Full Name:</label><br>
                                                    <input type="text" id="updateFullName'.$row['account_id'].'" name="updateFullName'.$row['account_id'].'" value="'.$row['account_fullname'].'" placeholder="Please enter full name" class="form-control">
                                                    <span class="error" id="errorUpdName'.$row['account_id'].'"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="updateUsername" class="text-danger">Username:</label><br>
                                                    <input type="text" id="updateUsername'.$row['account_id'].'" name="updateUsername'.$row['account_id'].'" placeholder="Please enter username" value="'.$row['account_username'].'" class="form-control">
                                                    <span class="error" id="errorUpdUsername'.$row['account_id'].'"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="updateEmail" class="text-danger">Email:</label><br>
                                                    <input type="email" id="updateEmail'.$row['account_id'].'" name="updateEmail'.$row['account_id'].'" placeholder="Please enter email address" value="'.$row['account_email'].'" class="form-control">
                                                    <span class="error" id="errorUpdEmail'.$row['account_id'].'"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ddEmployeeType" class="text-danger">Employee Type:</label>
                                                    <select id="ddEmployeeType'.$row['account_id'].'" name="ddEmployeeType'.$row['account_id'].'" class="form-select form-control" aria-label="Choose employee type">
                                                        <option value="1" selected>Employee</option>
                                                        <option value="2">Manager</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" name="submitUpdateInfo" onclick="validateUpdateInfo('.$row['account_id'].');" class="btn btn-success btn-md" value="Submit">
                                                </div>
                                                <div id="popup-button" class="text-right">
                                                    <button type="button" class="btn btn-danger btn-md" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';

                                    // Delete employee popup modal
                                    echo '<div class="modal fade deleteEmployeeConfirmationModal" id="deleteEmployeeConfirmationModal' . $row['account_id'] . '" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content -->
                                            <div id="modal-box" class="col-md-12">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <div class="modal-title">
                                                    <div class="text-center">
                                                        <i class="fa fa-exclamation-triangle fa-4x text-center" style="color:red"></i>
                                                        <h3 class="text-center text-danger">Confirm Delete Employee Account?</h3>
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete the Employee Account of ' . $row['account_fullname'] . '? This action cannot be undone!</p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" id="employeeDelete" name="employeeDelete" onclick="employeeDelete('.$row['account_id'].');" class="btn btn-success btn-md" value="Confirm">
                                                </div>
                                                <div id="popup-button" class="text-right">
                                                    <button type="button" class="btn btn-danger btn-md" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                }
                            } else {
                                echo "Sorry, no results found!";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- end table item -->
                <div class="table-responsive" id="table2">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Feedback Subject</th>
                                <th>Feedback Type</th>
                                <th>Mobile No.</th>
                                <th>Feedback Message</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            require('feedback.php');
                            $dbMapperFactory = new DBMapperFactory();
                            $feedback_mapper_instance = $dbMapperFactory->createMapperInstance("Feedback");
                            $conn = $feedback_mapper_instance->readFile("config.txt");
                            $result = $feedback_mapper_instance->readAllRowsFromDB($conn, "Feedback");
                            echo '<tr>';
                            if ($result->num_rows > 0) {
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo '<td>' . $row["fullname"] . '</td>';
                                    echo '<td>' . $row['email'] . '</td>';
                                    echo '<td>' . $row['feedback_subject'] . '</td>';
                                    echo '<td>' . $row['feedback_type'] . '</td>';
                                    echo '<td>' . $row['country_code'] . $row['mobile_no'] . '</td>';
                                    echo '<td>' . $row['feedback_content'] . '</td>';
                                    echo '<td><button id="btnRespondFeedback' . $row['feedback_id'] . '" onclick="respondFeedback(' . $row['feedback_id'] . ')" data-target="respondFeedbackModal' . $row['feedback_id'] . '" class="btn-info"><i class="fa fa-pencil"></i> Respond</button>';
                                    echo ' <button id="btnDeleteFeedback"' . $row['feedback_id'] . ' class="btn-danger" onclick="delFeedback(' . $row['feedback_id'] . ')" data-target="deleteFeedbackConfirmationModal' . $row['feedback_id'] . '"><i class="fa fa-trash"></i> Delete</button></td>';
                                    echo '</tr>';

                                    // Delete feedback popup modal
                                    echo '<div class="modal fade deleteFeedbackConfirmationModal" id="deleteFeedbackConfirmationModal' . $row['feedback_id'] . '" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content -->
                                            <div id="modal-box" class="col-md-12">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <div class="modal-title">
                                                    <div class="text-center">
                                                        <i class="fa fa-exclamation-triangle fa-4x text-center" style="color:red"></i>
                                                        <h3 class="text-center text-danger">Confirm Delete Feedback?</h3>
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete the feedback from ' . $row['fullname'] . '? This action cannot be undone!</p>
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit" id="feedbackDelete" name="feedbackDelete" onclick="feedbackDelete('.$row['feedback_id'].');" class="btn btn-success btn-md" value="Confirm">
                                                </div>
                                                <div id="popup-button" class="text-right">
                                                    <button type="button" class="btn btn-danger btn-md" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';

                                    // Respond feed back modal
                                    echo '<div class="modal fade respondFeedbackModal" id="respondFeedbackModal' . $row['feedback_id'] . '" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content -->
                                            <div id="modal-box" class="col-md-12">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <div class="modal-title">
                                                    <div class="text-center">
                                                        <h3 class="text-center text-danger">Send Feedback Email</h3>
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="senderEmail" class="text-danger">Sender name:</label><br>
                                                        <input type="text" id="senderName'.$row['feedback_id'].'" name="senderEmail'.$row['feedback_id'].'" placeholder="Enter sender name" value="" class="form-control">
                                                        <span class="error" id="errorSenderName'.$row['feedback_id'].'"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="senderEmail" class="text-danger">Sender email:</label><br>
                                                        <input type="text" id="senderEmail'.$row['feedback_id'].'" name="senderEmail'.$row['feedback_id'].'" placeholder="Enter sender email" value="fireaway3001@gmail.com" class="form-control" readonly>
                                                        <span class="error" id="errorSenderEmail'.$row['feedback_id'].'"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="recipientEmail" class="text-danger">Recipient Name:</label><br>
                                                        <input type="text" id="recipientName'.$row['feedback_id'].'" name="recipientName'.$row['feedback_id'].'" placeholder="Enter recipient fullname"  value="'.$row['fullname'].'" class="form-control" readonly>
                                                        <span class="error" id="errorRecipientName'.$row['feedback_id'].'"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="recipientEmail" class="text-danger">Recipient Email:</label><br>
                                                        <input type="text" id="recipientEmail'.$row['feedback_id'].'" name="recipientEmail'.$row['feedback_id'].'" placeholder="Enter recipient email"  value="'.$row['email'].'" class="form-control" readonly>
                                                        <span class="error" id="errorRecipientEmail'.$row['feedback_id'].'"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="subjectSender" class="text-danger">Subject:</label><br>
                                                        <input type="text" id="subjectSender'.$row['feedback_id'].'" name="subjectSender'.$row['feedback_id'].'" placeholder="Enter subject"  value="Re: '.$row['feedback_subject'].'" class="form-control">
                                                        <span class="error" id="errorSubjectSender'.$row['feedback_id'].'"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="feedbackMessage" class="text-danger">Feedback Message:</label><br>
                                                        <textarea type="textarea" class="feedbackMessage" id="feedbackMessage'.$row['feedback_id'].'" name="feedbackMessage'.$row['feedback_id'].'" placeholder="Enter feedback content" rows="10" class="form-control"></textarea>
                                                        <span class="error" id="errorFeedbackMessage'.$row['feedback_id'].'"></span>
                                                    </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="submit" id="feedbackRespond" name="feedbackRespond" onclick="feedbackRespond('.$row['feedback_id'].');" class="btn btn-success btn-md" value="Confirm">
                                                    </div>
                                                    <div id="popup-button" class="text-right">
                                                        <button type="button" class="btn btn-danger btn-md" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                echo "Sorry, no results found!";
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
    </section>

    <!--
    Add Employee Popup
    ==================================== -->
    <div class="modal fade" id="addEmployeeModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content -->
            <div id="modal-box" class="col-md-12">
                <form id="add-employee-form" class="form" action="" method="post" onsubmit="return validateRegister()">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="text-center text-danger">Add Employee Account</h3>
                    <div class="form-group">
                        <label for="newFullName" class="text-danger">Full Name:</label><br>
                        <input type="text" id="newFullName" name="newFullName" placeholder="Please enter employee's full name" class="form-control">
                        <span class="error" id="errorNewName"></span>
                    </div>
                    <div class="form-group">
                        <label for="newUsername" class="text-danger">Username:</label><br>
                        <input type="text" id="newUsername" name="newUsername" placeholder="Please enter employee's username" class="form-control">
                        <span class="error" id="errorNewUsername"></span>
                    </div>
                    <div class="form-group">
                        <label for="newEmail" class="text-danger">Email:</label><br>
                        <input type="email" id="newEmail" name="newEmail" placeholder="Please enter employee's email address" class="form-control">
                        <span class="error" id="errorNewEmail"></span>
                    </div>
                    <div class="form-group">
                        <label for="newPassword" class="text-danger">Password:</label><br>
                        <input type="password" id="newPassword" name="newPassword" placeholder="Please assign an employee's password" class="form-control">
                        <span class="error" id="errorNewPassword"></span>
                    </div>
                    <div class="form-group">
                        <label for="newEmployeeType" class="text-danger">Employee Type:</label>
                        <select id="newEmployeeType" class="form-select form-control" aria-label="Choose employee type">
                            <option value="1" selected>Employee</option>
                            <option value="2">Manager</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-success btn-md" value="Submit">
                    </div>
                    <div id="popup-button" class="text-right">
                        <button type="button" class="btn btn-danger btn-md" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div>

    <?php include('footer.php'); ?>

    <!-- Essential jQuery Plugins
    ================================================== -->
    <!-- Main jQuery -->
    <script src="js/jquery-1.11.1.min.js"></script>

    <!-- Contact form validation -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>

    <!-- Dashboard Functions -->
    <script type="text/javascript" src="js/dashboard.js"></script>

    <!-- Header Functions -->
    <script type="text/javascript" src="js/header.js"></script>

    <!-- Custom Functions -->
    <script src="js/custom.js"></script>

    <!-- validate Functions -->
    <script src="js/validationInput.js"></script>

    <!-- Account Functions  -->
    <script src="js/account.js"></script>

</body>


</html>
