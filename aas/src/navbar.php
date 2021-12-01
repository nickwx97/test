<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Team AAS</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <?php if (isset($_SESSION['username'])) { ?>

        <ul class="navbar-nav">
            <?php if ($_SESSION['role'] == 'admin') { ?>
            <li class="nav-item"><a class="nav-link" href="admin.php"></span> Admin
                    Index</a></li>
            <li class="nav-item"><a class="nav-link"
                    href="changePassword.php?editId=<?php echo $_SESSION['user_id'] ?>"></span> Change Password</a></li>
            <?php } else if ($_SESSION['role'] == 'guest') { ?>
            <!-- <li class="nav-item"><a class="nav-link" href="guestAppt.php"></span> Guest Index</a></li> -->
            <li class="nav-item"><a class="nav-link" href="services.php"></span> Our Services</a></li>
            <li class="nav-item"><a class="nav-link"
                    href="changePassword.php?editId=<?php echo $_SESSION['user_id'] ?>"></span> Change Password</a></li>
            <?php } else if ($_SESSION['role'] == 'employee') { ?>
            <li class="nav-item"><a class="nav-link" href="employee.php"></span>
                    Employee Index</a></li>
            <li class="nav-item"><a class="nav-link"
                    href="changePassword.php?editId=<?php echo $_SESSION['user_id'] ?>"></span> Change Password</a></li>

            <?php } ?>
            &nbsp; &nbsp;
            <li class="nav-item dropdown">
                <a class="btn btn-secondary dropdown-toggle" id="navbardrop" data-toggle="dropdown"
                    style="background-color: #a0a0a0;">
                    My Account
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <?php if ($_SESSION['role'] == 'employee') { ?>

                    <a class="dropdown-item"
                        href="employeeProfile.php?editId=<?php echo $_SESSION['user_id'] ?>">Employee
                        Edit
                        Profile</a>

                    <?php } else if ($_SESSION['role'] == 'guest' || $_SESSION['role'] == 'admin') { ?>
                    <a class="dropdown-item" href="editProfile.php?editId=<?php echo $_SESSION['user_id'] ?>">Edit
                        Profile</a>

                    <?php } ?>

                    <?php if ($_SESSION['role'] == 'guest') { ?>
                    <a class="dropdown-item" href="myBooking.php">My Bookings</a>
                    <?php } else if ($_SESSION['role'] == 'employee') { ?>
                    <a class="dropdown-item" href="myAppt.php">My
                        Appointments</a>
                    <?php } ?>
                    <a class=" dropdown-item" href="logout.php">Logout</a>
                </div>

                <?php } else { ?>
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="services.php"></span> Our Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php"></span> Login</a></li>
                </ul>
                <?php } ?>
            </li>
        </ul>
    </div>
</nav>